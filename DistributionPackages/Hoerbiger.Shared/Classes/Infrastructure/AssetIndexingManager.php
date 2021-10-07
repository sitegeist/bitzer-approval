<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\AssetAdapter;
use Hoerbiger\Shared\Domain\Asset\AssetAdapterFactory;
use Hoerbiger\Shared\Domain\Asset\AssetCollectionAdapter;
use Hoerbiger\Shared\Domain\Asset\AssetDirectory;
use Hoerbiger\Shared\Domain\Asset\AssetDirectoryRepository;
use Hoerbiger\Shared\Domain\AssetSource\LanguageAwareAssetSourceIdentifier;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Exception\NodeException;
use Neos\ContentRepository\Search\Indexer\NodeIndexerInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Browser\Domain\Session\BrowserState;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Document;

/**
 * The asset indexing manager infrastructure service
 *
 * @Flow\Scope("singleton")
 */
final class AssetIndexingManager
{
    private NodeIndexerInterface $nodeIndexer;

    private AssetAdapterFactory $assetAdapterFactory;

    private AssetDirectoryRepository $assetDirectoryRepository;

    private BrowserState $browserState;

    private PersistenceManagerInterface $persistenceManager;

    public function __construct(
        NodeIndexerInterface $nodeIndexer,
        AssetAdapterFactory $assetAdapterFactory,
        AssetDirectoryRepository $assetDirectoryRepository,
        BrowserState $browserState,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->nodeIndexer = $nodeIndexer;
        $this->assetAdapterFactory = $assetAdapterFactory;
        $this->assetDirectoryRepository = $assetDirectoryRepository;
        $this->browserState = $browserState;
        $this->persistenceManager = $persistenceManager;
    }

    public function whenAssetWasCreated(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->indexAsset($asset);
        }
    }

    public function whenAssetWasUpdated(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->indexAsset($asset);
        }
    }

    public function whenAssetWasRemoved(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->removeAssetFromIndex($asset);
        }
    }

    private function indexAsset(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $assetAdapter = $this->resolveAssetAdapter($asset);
            if ($assetAdapter) {
                $this->nodeIndexer->indexNode($assetAdapter);
            }
        }
    }

    private function removeAssetFromIndex(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $assetAdapter = $this->resolveAssetAdapter($asset);
            if ($assetAdapter) {
                $this->nodeIndexer->removeNode($assetAdapter);
            }
        }
    }

    private function resolveAssetAdapter(Document $document): ?AssetAdapter
    {
        $assetSourceIdentifier = LanguageAwareAssetSourceIdentifier::fromString($document->getAssetSourceIdentifier());

        $assetDirectory = $this->assetDirectoryRepository->findAssetDirectory($assetSourceIdentifier->getLanguage());
        return $assetDirectory
            ? $this->assetAdapterFactory->create(
                $document,
                $assetDirectory,
                $this->resolveAssetCollection($assetDirectory)
            )
            : null;
    }

    private function resolveAssetCollection(AssetDirectory $assetDirectory): ?AssetCollectionAdapter
    {
        if ($this->browserState->get('activeAssetCollection')) {
            try {
                /** @var ?AssetCollection $activeAssetCollection */
                $activeAssetCollection = $this->browserState->get('activeAssetCollection');
                return $assetDirectory->findNamedChildNode(
                    NodeName::fromString(
                        $activeAssetCollection
                            ? $this->persistenceManager->getIdentifierByObject($activeAssetCollection)
                            : ''
                    )
                );
            } catch (NodeException $e) {
                // then don't
            }
        }

        $assetCollections = $assetDirectory->getChildNodes();
        return reset($assetCollections) ?: null;
    }
}
