<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ContentRepository;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\PublishingService;

/**
 * @Flow\Scope("singleton")
 */
class NodeTranslationService
{
    /**
     * @Flow\Inject
     * @var PublishingService
     */
    protected $publishingService;


    #[Flow\InjectConfiguration(path: 'nodeTranslation.enabled', package: 'Sitegeist.LostInTranslation')]
    protected bool $enabled;

    /**
     * @var array<string,mixed>
     */
    #[Flow\InjectConfiguration(path: 'autoTranslationReview.assignments')]
    protected array $translationTaskAssignmentSettings;


    #[Flow\Inject]
    protected \Sitegeist\LostInTranslation\ContentRepository\NodeTranslationService $nodeTranslationService;

    /**
     * @param NodeInterface $node
     * @param Context $context
     * @param bool $recursive
     * @return void
     */
    public function afterAdoptNode(NodeInterface $node, Context $context, bool $recursive): void
    {
        if (!$this->enabled) {
            return;
        }

        $isAutomaticTranslationEnabledForNodeType = $node->getNodeType()->getConfiguration(
            'options.automaticTranslation'
        ) ?? true;

        if (!$isAutomaticTranslationEnabledForNodeType) {
            return;
        }

        $language = $node->getContext()->getTargetDimensions()['language'];


        if (array_key_exists($language, $this->translationTaskAssignmentSettings)) {
            $adoptedNode = $context->getNodeByIdentifier($node->getIdentifier());

            if ($adoptedNode instanceof NodeInterface) {
                $this->nodeTranslationService->translateNode($node, $adoptedNode, $context);
            }
        }
    }
}
