<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview;

use Neos\ContentRepository\DimensionSpace\Dimension\ContentDimensionIdentifier;
use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use Sitegeist\Bitzer\Application\Bitzer;
use Sitegeist\Bitzer\Domain\Agent\Agent;
use Sitegeist\Bitzer\Domain\Task\Command\CancelTask;
use Sitegeist\Bitzer\Domain\Task\Command\CompleteTask;
use Sitegeist\Bitzer\Domain\Task\Command\ScheduleTask;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Domain\Task\Schedule;
use Sitegeist\Bitzer\Domain\Task\TaskClassName;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;

use function PHPUnit\Framework\stringStartsWith;

#[Flow\Scope('singleton')]
final class AutoTranslationReviewTaskZookeeper
{
    #[Flow\InjectConfiguration(path: 'nodeTranslation.enabled', package: 'Sitegeist.LostInTranslation')]
    protected bool $enabled;

    #[Flow\InjectConfiguration(path: 'nodeTranslation.skipAuthorizationChecks', package: 'Sitegeist.LostInTranslation')]
    protected bool $skipAuthorizationChecks;

    /**
     * @var array<string,mixed>
     */
    #[Flow\InjectConfiguration(path: 'autoTranslationReview.assignments')]
    protected array $translationTaskAssignmentSettings;


    /**
     * @var array<int,string>
     */
    protected array $newlyPublishedDocumentsCache;
    public function __construct(
        private readonly SecurityContext $securityContext,
        private readonly Bitzer $bitzer,
        private readonly Schedule $schedule,
        private readonly ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        private readonly ContentContextFactory $contentContextFactory,
        private readonly PersistenceManagerInterface $persistenceManager,
    ) {
        $this->newlyPublishedDocumentsCache = [];
    }

    public function beforeNodeWasPublished(Node $node, Workspace $workspace): void
    {
        if (!$this->enabled) {
            return;
        }

        if ($workspace->getName() !== 'live') {
            return;
        }

        $liveContentContext = $this->getContextForLanguageDimensionAndWorkspaceName('en');

        if ($node->getContext()->getDimensions()['language'][0] === 'en'
            && $node->getNodeType()->isOfType('Neos.Neos:Document')
        ) {
            $presentDocumentNode = $liveContentContext->getNodeByIdentifier($node->getIdentifier());

            if (!$presentDocumentNode) {
                $this->newlyPublishedDocumentsCache[] = $node->getPath();
            }
        }
    }

    public function whenNodeWasPublished(Node $node, Workspace $workspace): void
    {
        if (!$this->enabled) {
            return;
        }

        if ($workspace->getName() !== 'live') {
            return;
        }

        $language = $node->getContext()->getTargetDimensions()['language'];

        if (array_key_exists($language, $this->translationTaskAssignmentSettings)) {
            foreach ($this->translationTaskAssignmentSettings[$language] as $targetLanguage => $targetConfiguration) {
                $createReviewTask = false;
                if ($this->skipAuthorizationChecks) {
                    $this->securityContext->withoutAuthorizationChecks(
                        function () use ($node, $targetLanguage, &$createReviewTask) {
                            $createReviewTask = $this->syncNode($node, $targetLanguage);
                        }
                    );
                } else {
                    $createReviewTask = $this->syncNode($node, $targetLanguage);
                }
                if ($createReviewTask) {
                    $dimensionSpacePoint = DimensionSpacePoint::fromLegacyDimensionArray(
                        $node->getContext()->getDimensions()
                    )
                        ->vary(new ContentDimensionIdentifier('language'), $targetLanguage);

                    $completedObject = $node;
                    while ($completedObject && !$completedObject->getNodeType()->isOfType('Neos.Neos:Document')) {
                        $completedObject = $completedObject->getParent();
                    }
                    assert($completedObject instanceof Node);
                    $objectAddress = new NodeAddress(
                        'live', // will be replaced in the backend module
                        $dimensionSpacePoint,
                        $completedObject->getNodeAggregateIdentifier(),
                    );
                    $presentTaskIds = $this->schedule->findActiveOrPotentialTaskIdsForObject(
                        $objectAddress,
                        TaskClassName::createFromString(AutoTranslationReviewTask::class),
                    );
                    if (!empty($presentTaskIds)) {
                        continue;
                    }

                    foreach ($targetConfiguration['agents'] as $agent) {
                        $this->bitzer->handleScheduleTask(new ScheduleTask(
                            TaskIdentifier::create(),
                            TaskClassName::createFromString(AutoTranslationReviewTask::class),
                            (new \DateTimeImmutable())
                                ->add(new \DateInterval($targetConfiguration['interval'] ?? 'P1D')),
                            Agent::fromRole(new Role($agent)),
                            $objectAddress,
                            null,
                            [
                                'description' => 'autogenerated translation review task'
                            ]
                        ));
                    }
                }
            }
        }

        $this->flagCompletedTasks($node, $language);
    }

    private function flagCompletedTasks(Node $node, string $language): void
    {
        $objectNode = $node;
        while ($objectNode && !$objectNode->getNodeType()->isOfType('Neos.Neos:Document')) {
            $objectNode = $objectNode->getParent();
        }
        if (!$objectNode instanceof Node || $objectNode->isHidden()) {
            return;
        }
        $completedObject = new NodeAddress(
            'live',
            DimensionSpacePoint::fromLegacyDimensionArray($node->getContext()->getDimensions())
                ->vary(
                    new ContentDimensionIdentifier('language'),
                    $language
                ),
            $objectNode->getNodeAggregateIdentifier()
        );
        foreach ($this->schedule->findActiveOrPotentialTaskIdsForObject($completedObject) as $taskIdToComplete) {
            $this->bitzer->handleCompleteTask(new CompleteTask($taskIdToComplete));
        }
    }

    /**
     * @return bool whether a review task is to be created
     */
    private function syncNode(Node $sourceNode, string $targetLanguage): bool
    {
        $isAutomaticTranslationEnabledForNodeType = $sourceNode->getNodeType()
            ->getConfiguration('options.automaticTranslation') ?? true;
        if (!$isAutomaticTranslationEnabledForNodeType) {
            return false;
        }

        if (!$sourceNode->isRemoved()) {
            $targetContext = $this->getContextForLanguageDimensionAndWorkspaceName($targetLanguage);
            $targetContext->getFirstLevelNodeCache()->flush();

            $presentNode = $targetContext->getNodeByIdentifier($sourceNode->getIdentifier());
            if ($presentNode) {
                // the updated node has to be reviewed
                return true;
            }

            if (!$sourceNode->getNodeType()->isOfType('Neos.Neos:Document')) {
                $insideNewDocument = false;

                foreach ($this->newlyPublishedDocumentsCache as $newlyPublishedDocumentPath) {
                    if (str_starts_with($sourceNode->getParentPath(), $newlyPublishedDocumentPath)) {
                        $insideNewDocument = true;
                        break;
                    }
                }
                if (!$insideNewDocument) {
                    return true;
                }
            }


            $this->persistenceManager->persistAll();

            $targetNode = $targetContext->adoptNode($sourceNode);

            if ($targetNode instanceof Node && $targetNode->getNodeType()->isOfType('Neos.Neos:Document')) {
                // disable newly published documents
                $targetNode->setHidden(true);
                $targetNode->setLastPublicationDateTime(new \DateTimeImmutable('now'));
            }

            // Move node if targetNode has no parent or node parents are not matching
            /** @var ?Node $targetParent */
            $targetParent = $targetNode->getParent();
            if (!$targetParent || ($sourceNode->getParentPath() !== $targetNode->getParentPath())) {
                $sourceParentNode = $sourceNode->getParent();
                assert($sourceParentNode instanceof Node);
                $referenceNode = $targetContext->getNodeByIdentifier($sourceParentNode->getIdentifier());
                if ($referenceNode instanceof NodeInterface) {
                    $targetNode->moveInto($referenceNode);
                }
            }

            $targetContext->getFirstLevelNodeCache()->flush();
        } else {
            $removeContext = $this->getContextForLanguageDimensionAndWorkspaceName($targetLanguage);
            $targetNode = $removeContext->getNodeByIdentifier($sourceNode->getIdentifier());
            if ($targetNode !== null) {
                $targetNode->remove();
                $toBeRemovedObject = new NodeAddress(
                    'live',
                    DimensionSpacePoint::fromLegacyDimensionArray($sourceNode->getContext()->getDimensions())
                        ->vary(
                            new ContentDimensionIdentifier('language'),
                            $targetLanguage
                        ),
                    $sourceNode->getNodeAggregateIdentifier()
                );

                $activeAutoTranslationReviewTaskIds = $this->schedule->findActiveOrPotentialTaskIdsForObject(
                    $toBeRemovedObject,
                    TaskClassName::createFromString(AutoTranslationReviewTask::class)
                );
                foreach ($activeAutoTranslationReviewTaskIds as $activeAutoTranslationReviewTaskId) {
                    $this->bitzer->handleCancelTask(new CancelTask(
                        $activeAutoTranslationReviewTaskId
                    ));
                }
            }
        }

        return true;
    }

    private function getContextForLanguageDimensionAndWorkspaceName(
        string $language,
    ): Context {
        $dimensionValues = $this->contentDimensionPresetSource->getAllPresets()
            ['language']['presets'][$language]['values'];

        return $this->contentContextFactory->create(
            array(
                'workspaceName' => 'live',
                'invisibleContentShown' => true,
                'removedContentShown' => true,
                'inaccessibleContentShown' => true,
                'dimensions' => [
                    'language' => $dimensionValues
                ],
                'targetDimensions' => [
                    'language' => reset($dimensionValues)
                ]
            )
        );
    }
}
