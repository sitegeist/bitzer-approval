<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Command;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Sitegeist\LostInTranslation\Domain\TranslationServiceInterface;
use Sitegeist\MagicWand\Command\AbstractCommandController;

/**
 * @Flow\Scope("singleton")
 */
class TranslationCommandController extends AbstractCommandController
{
    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var TranslationServiceInterface
     */
    protected $translationService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    public function resetCommand(): void
    {
        $sourceContext = $this->createContentContext('de');
        $targetContext = $this->createContentContext('en');

        $rootNode = $sourceContext->getCurrentSiteNode();
        $this->resetNode($rootNode, $targetContext);
    }

    private function resetNode(NodeInterface $node, ContentContext $targetContext): void
    {
        if ($node->getIdentifier() === '6e41f976-3e9d-4d46-ac19-808bc22e5887' // Wasserstoff
            || $node->getIdentifier() === 'd0669f6b-37da-4178-8d40-1560e6328395' // Produkte für Kolbenkompressoren
            || $node->getIdentifier() === '9f494fc9-274f-4eb4-9102-d4706597501f' // Services für Kolbenkompressoren
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
            $this->resetNode($childNode, $targetContext);
        }
    }

    public function refreshCommand(): void
    {
        $sourceContext = $this->createContentContext('de');
        $targetContext = $this->createContentContext('en');

        $rootNode = $sourceContext->getCurrentSiteNode();
        $this->refreshNode($rootNode, $targetContext);
    }

    private function refreshNode(NodeInterface $node, ContentContext $targetContext): void
    {
        if ($node->getNodeType()->getName() === 'Hoerbiger.Shared:Document.AssetDirectory') {
            return;
        }
        if ($node->getIdentifier() === '6e41f976-3e9d-4d46-ac19-808bc22e5887' // Wasserstoff
            || $node->getIdentifier() === 'd0669f6b-37da-4178-8d40-1560e6328395' // Produkte für Kolbenkompressoren
            || $node->getIdentifier() === '9f494fc9-274f-4eb4-9102-d4706597501f' // Services für Kolbenkompressoren
        ) {
            // already properly translated
            return;
        }
        /** @var ?NodeInterface $targetNode */
        $targetNode = $targetContext->getNodeByIdentifier($node->getIdentifier());
        if (is_null($targetNode)) {
            $this->output->outputLine('Translating node ' . $node->getLabel());
            $targetContext->adoptNode($node);
        } elseif ($node->getNodeType()->isOfType('Hoerbiger.HoerbigerCom:Mixin.Introduction')) {
            $this->output->outputLine('Translating properties of node ' . $node->getLabel());
            $texts = [
                'abstract' => $node->getProperty('abstract') ?: '',
                'headline' => $node->getProperty('headline') ?: '',
            ];
            $translations = $this->translationService->translate(
                $texts,
                'en',
                'de'
            );
            foreach ($translations as $propertyName => $translation) {
                $targetNode->setProperty($propertyName, $translation);
            }
        }

        foreach ($node->getChildNodes() as $childNode) {
            $this->refreshNode($childNode, $targetContext);
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
