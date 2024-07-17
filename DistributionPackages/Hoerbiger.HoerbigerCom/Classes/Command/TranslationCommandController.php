<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Command;

use Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview\AutoTranslationReviewTask;
use Neos\ContentRepository\DimensionSpace\Dimension\ContentDimensionIdentifier;
use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Sitegeist\Bitzer\Application\Bitzer;
use Sitegeist\Bitzer\Domain\Agent\Agent;
use Sitegeist\Bitzer\Domain\Task\Command\ScheduleTask;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Domain\Task\Schedule;
use Sitegeist\Bitzer\Domain\Task\TaskClassName;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;
use Sitegeist\LostInTranslation\ContentRepository\NodeTranslationService;
use Sitegeist\LostInTranslation\Domain\TranslationServiceInterface;

#[Flow\Scope('singleton')]
class TranslationCommandController extends CommandController
{
    /**
     * @var array<string,mixed>
     */
    #[Flow\InjectConfiguration(path: 'autoTranslationReview.assignments')]
    protected array $translationTaskAssignmentSettings;

    public function __construct(
        private readonly ContentContextFactory $contentContextFactory,
        private readonly TranslationServiceInterface $translationService,
        private readonly NodeTranslationService $nodeTranslationService,
        private readonly PersistenceManagerInterface $persistenceManager,
        private readonly Bitzer $bitzer,
        private readonly Schedule $schedule,
    ) {
        parent::__construct();
    }

    public function resetCommand(string $sourceLanguage, string $targetLanguage): void
    {
        $sourceContext = $this->createContentContext($sourceLanguage);
        $targetContext = $this->createContentContext($targetLanguage);

        $rootNode = $sourceContext->getCurrentSiteNode();
        $this->resetNode($rootNode, $targetContext, $targetLanguage);
    }

    private function resetNode(NodeInterface $node, ContentContext $targetContext, string $targetLanguage): void
    {
        if ($targetLanguage === 'en'
            && ($node->getIdentifier() === '6e41f976-3e9d-4d46-ac19-808bc22e5887' // Wasserstoff
                || $node->getIdentifier() === 'd0669f6b-37da-4178-8d40-1560e6328395' // Produkte für Kolbenkompressoren
                || $node->getIdentifier() === '9f494fc9-274f-4eb4-9102-d4706597501f' // Services für Kolbenkompressoren
            )
        ) {
            // already properly translated
            return;
        }

        if ($node->getNodeType()->isOfType('Neos.Neos:Content')) {
            /** @var ?NodeInterface $targetNode */
            $targetNode = $targetContext->getNodeByIdentifier($node->getIdentifier());
            if (!is_null($targetNode)) {
                $this->output->outputLine('Resetting node ' . $node->getLabel());
                $targetNode->remove();
            }
        }

        foreach ($node->getChildNodes() as $childNode) {
            $this->resetNode($childNode, $targetContext, $targetLanguage);
        }
    }

    public function refreshCommand(string $sourceLanguage, string $targetLanguage): void
    {
        $sourceContext = $this->createContentContext($sourceLanguage);
        $targetContext = $this->createContentContext($targetLanguage);

        $rootNode = $sourceContext->getCurrentSiteNode();

        $this->refreshNode($rootNode, $targetContext, $sourceLanguage, $targetLanguage);
    }

    private function refreshNode(
        NodeInterface $node,
        ContentContext $targetContext,
        string $sourceLanguage,
        string $targetLanguage
    ): void {
        if ($node->getNodeType()->getName() === 'Hoerbiger.Shared:Document.AssetDirectory') {
            return;
        }
        if ($targetLanguage === 'en'
            && ($node->getIdentifier() === '6e41f976-3e9d-4d46-ac19-808bc22e5887' // Wasserstoff
            || $node->getIdentifier() === 'd0669f6b-37da-4178-8d40-1560e6328395' // Produkte für Kolbenkompressoren
            || $node->getIdentifier() === '9f494fc9-274f-4eb4-9102-d4706597501f' // Services für Kolbenkompressoren
            )
        ) {
            // already properly translated
            return;
        }
        /** @var ?Node $targetNode */
        $targetNode = $targetContext->getNodeByIdentifier($node->getIdentifier());

        $createReviewTask = false;
        if (is_null($targetNode) || !$targetNode->getDimensions()) {
            $this->output->outputLine('Translating node ' . $node->getLabel());
            /** @var Node $targetNode */
            $targetNode = $targetContext->adoptNode($node);
            $this->nodeTranslationService->translateNode($node, $targetNode, $targetContext);
            if ($targetNode->getNodeType()->isOfType('Neos.Neos:Document')) {
                $targetNode->setHidden(true);
                $createReviewTask = true;
            }
        } elseif ($targetNode->isTethered()) {
            $this->nodeTranslationService->translateNode($node, $targetNode, $targetContext);
            if ($targetNode->getNodeType()->isOfType('Neos.Neos:Document')) {
                $createReviewTask = true;
            }
        } elseif ($node->getNodeType()->isOfType('Hoerbiger.HoerbigerCom:Mixin.Introduction')) {
            $this->output->outputLine('Translating properties of node ' . $node->getLabel());
            $texts = [
                'abstract' => $node->getProperty('abstract') ?: '',
                'headline' => $node->getProperty('headline') ?: '',
            ];
            $translations = $this->translationService->translate(
                $texts,
                $targetLanguage,
                $sourceLanguage
            );
            foreach ($translations as $propertyName => $translation) {
                $targetNode->setProperty($propertyName, $translation);
            }
            $createReviewTask = true;
        }
        $targetNode->setIndex($node->getIndex());
        $targetNode->setHiddenInIndex($node->isHiddenInIndex());

        if ($createReviewTask) {
            $dimensionSpacePoint = DimensionSpacePoint::fromLegacyDimensionArray(
                $node->getContext()->getDimensions()
            )->vary(
                new ContentDimensionIdentifier('language'),
                $targetLanguage
            );

            $objectAddress = new NodeAddress(
                'live', // will be replaced in the backend module
                $dimensionSpacePoint,
                $targetNode->getNodeAggregateIdentifier(),
            );
            $presentTaskIds = $this->schedule->findActiveOrPotentialTaskIdsForObject(
                $objectAddress,
                TaskClassName::createFromString(AutoTranslationReviewTask::class),
            );
            if (empty($presentTaskIds)) {
                $targetConfiguration = $this->translationTaskAssignmentSettings[$sourceLanguage][$targetLanguage];
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

        foreach ($node->getChildNodes() as $childNode) {
            $this->refreshNode($childNode, $targetContext, $sourceLanguage, $targetLanguage);
        }

        if ($node->getNodeType()->isOfType('Neos.Neos:Document')) {
            $this->persistenceManager->persistAll();
        }
    }

    private function createContentContext(string $language): ContentContext
    {
        /** @var ContentContext $contentContext */
        $contentContext = $this->contentContextFactory->create([
            'dimensions' => ['language' => [$language]],
            'targetDimensions' => ['language' => $language],
            'invisibleContentShown' => true,
            'removedContentShown' => true,
            'inaccessibleContentShown' => true
        ]);

        return $contentContext;
    }
}
