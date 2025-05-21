<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Infrastructure\ControllerEnvironmentService;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Neos\Domain\Service\ContentContext;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TranslationExport
{
    protected ControllerContext $controllerContext;
    public function __construct(
        private readonly TranslationContentContextFactory $contentContextFactory,
        private readonly ControllerEnvironmentService $controllerEnvironmentService,
        private readonly ExportRecordFactory $exportRecordFactory,
        private readonly NodeTypeConstraintFactory $nodeTypeConstraintFactory,
    ) {
    }

    public function initializeObject(): void
    {
        $this->controllerContext = $this->controllerEnvironmentService->getControllerContext();
    }

    public function forLanguage(string $targetLanguage): void
    {
        $exportFile = new SiteExportFile(new Spreadsheet(), $targetLanguage);

        /** @var ContentContext $sourceContext */
        $sourceContext = $this->contentContextFactory->createForLanguageDimensionAndWorkspaceName(
            'en'
        );
        /** @var ContentContext $targetContext */
        $targetContext = $this->contentContextFactory->createForLanguageDimensionAndWorkspaceName(
            $targetLanguage
        );
        /** @var TraversableNodeInterface $rootNode */
        $rootNode = $sourceContext->getCurrentSiteNode();

        $documentNodes = $this->findDocumentNodesRecursively($rootNode);

        foreach ($documentNodes as $documentNode) {
            $targetDocumentNode = $targetContext->getNodeByIdentifier(
                (string)$documentNode->getNodeAggregateIdentifier()
            );

            if (!$targetDocumentNode instanceof TraversableNodeInterface) {
                continue;
            }

            $targetDocumentNodeUri = $this->getNodeUri(
                $targetDocumentNode
            );

            $this->writeNodeInExport(
                $targetDocumentNodeUri,
                $documentNode,
                $targetDocumentNode,
                $exportFile
            );

            $sourceChildNodes = $this->findContentNodesRecursively($documentNode);

            foreach ($sourceChildNodes as $sourceChildNode) {
                $targetChildNode = $targetContext->getNodeByIdentifier(
                    (string)$sourceChildNode->getNodeAggregateIdentifier()
                );

                if (!$targetChildNode instanceof TraversableNodeInterface) {
                    continue;
                }

                $this->writeNodeInExport($targetDocumentNodeUri, $sourceChildNode, $targetChildNode, $exportFile);
            }
        }

        $exportFile->persist($targetLanguage);
    }

    public function forDocument(
        string $documentNodeIdentifier,
        string $targetLanguage
    ): ?DocumentExportFile {
        /** @var ContentContext $sourceContext */
        $sourceContext = $this->contentContextFactory->createForLanguageDimensionAndWorkspaceName(
            'en'
        );
        /** @var ContentContext $targetContext */
        $targetContext = $this->contentContextFactory->createForLanguageDimensionAndWorkspaceName(
            $targetLanguage
        );

        $documentNodeInSourceContext = $sourceContext->getNodeByIdentifier(
            $documentNodeIdentifier
        );

        $documentNodeInTargetContext = $targetContext->getNodeByIdentifier(
            $documentNodeIdentifier
        );

        if (!$documentNodeInSourceContext instanceof TraversableNodeInterface
            || !$documentNodeInTargetContext instanceof TraversableNodeInterface
        ) {
            return null;
        }

        $exportFile = new DocumentExportFile(
            new Spreadsheet(),
            $targetLanguage,
            $this->getNodeUri($documentNodeInTargetContext) ?: new Uri('/')
        );

        $this->writeNodeInExport(
            null,
            $documentNodeInSourceContext,
            $documentNodeInTargetContext,
            $exportFile
        );

        $sourceChildNodes = $this->findContentNodesRecursively($documentNodeInSourceContext);

        foreach ($sourceChildNodes as $sourceChildNode) {
            $targetChildNode = $targetContext->getNodeByIdentifier(
                (string)$sourceChildNode->getNodeAggregateIdentifier()
            );

            if (!$targetChildNode instanceof TraversableNodeInterface) {
                continue;
            }

            $this->writeNodeInExport(null, $sourceChildNode, $targetChildNode, $exportFile);
        }

        return $exportFile;
    }
    private function findDocumentNodesRecursively(TraversableNodeInterface $documentNode): TraversableNodes
    {
        $childNodes = $documentNode->findChildNodes(
            $this->nodeTypeConstraintFactory->parseFilterString(
                'Neos.Neos:Document'
            )
        );
        foreach ($childNodes as $childNode) {
            $childNodes = $childNodes->merge($this->findDocumentNodesRecursively($childNode));
        }
        return $childNodes;
    }

    private function findContentNodesRecursively(TraversableNodeInterface $node): TraversableNodes
    {
        $childNodes = $node->findChildNodes(
            $this->nodeTypeConstraintFactory->parseFilterString(
                'Neos.Neos:Content, Neos.Neos:ContentCollection'
            )
        );

        foreach ($childNodes as $childNode) {
            $childNodes = $childNodes->merge($this->findContentNodesRecursively($childNode));
        }
        return $childNodes;
    }

    private function writeNodeInExport(
        ?Uri $documentNodeUri,
        TraversableNodeInterface $sourceNode,
        TraversableNodeInterface $targetNode,
        ExportFileInterface $exportFile
    ): void {
        $exportRecords = $this->exportRecordFactory->findRecordsForNode($documentNodeUri, $sourceNode, $targetNode);
        foreach ($exportRecords as $exportRecord) {
            $exportFile->writeNewRow(
                $exportRecord,
            );
        }
    }


    private function getNodeUri(NodeInterface $document): ?Uri
    {

        $uriBuilder = clone $this->controllerContext->getUriBuilder();

        $uriBuilder = $uriBuilder->reset()->setCreateAbsoluteUri(true)->setFormat('html');

        try {
            $uri = $uriBuilder->uriFor(
                actionName: 'show',
                controllerArguments: ['node' => $document],
                controllerName: 'Frontend\Node',
                packageKey: 'Neos.Neos',
            );

            $uri = str_replace('/././', '/', $uri);
        } catch (\Throwable $exception) {
            return null;
        }

        return new Uri($uri);
    }
}
