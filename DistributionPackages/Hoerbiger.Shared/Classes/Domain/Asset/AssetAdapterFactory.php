<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Repository\DocumentRepository;

/**
 * The asset adapter domain factory
 *
 * @Flow\Scope("singleton")
 */
final class AssetAdapterFactory
{
    private PersistenceManagerInterface $persistenceManager;

    private DocumentRepository $documentRepository;

    private NodeTypeManager $nodeTypeManager;

    private PdfTranslatorInterface $pdfTranslator;

    public function __construct(
        PersistenceManagerInterface $persistenceManager,
        DocumentRepository $documentRepository,
        NodeTypeManager $nodeTypeManager,
        PdfTranslatorInterface $pdfTranslator
    ) {
        $this->persistenceManager = $persistenceManager;
        $this->documentRepository = $documentRepository;
        $this->nodeTypeManager = $nodeTypeManager;
        $this->pdfTranslator = $pdfTranslator;
    }

    public function create(
        Document $document,
        AssetDirectory $assetDirectory,
        ?AssetCollectionAdapter $assetCollectionAdapter = null,
        int $index = 0
    ): ?AssetAdapter {
        if (!$assetCollectionAdapter) {
            $assetCollection = $document->getAssetCollections()->current();
            if (!$assetCollection) {
                return null;
            }
            $assetCollectionAdapter = new AssetCollectionAdapter(
                $assetCollection,
                $this->persistenceManager->getIdentifierByObject($assetCollection),
                $assetDirectory,
                0,
                $this->documentRepository,
                $this->nodeTypeManager,
                $this
            );
        }

        return new AssetAdapter(
            $document,
            $this->pdfTranslator->translateToCreativeWork($document),
            $assetCollectionAdapter,
            $assetDirectory,
            $index,
            $this->nodeTypeManager->getNodeType(AssetAdapter::NODE_TYPE_NAME)
        );
    }
}
