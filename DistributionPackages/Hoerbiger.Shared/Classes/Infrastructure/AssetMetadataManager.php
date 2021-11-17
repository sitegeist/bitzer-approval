<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\AssetMetadata;
use Hoerbiger\Shared\Domain\Asset\AssetMetadataRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Fusion\Core\Cache\ContentCache;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Document;

/**
 * The asset indexing manager infrastructure service
 *
 * @Flow\Scope("singleton")
 */
final class AssetMetadataManager
{
    protected AssetMetadataRepository $assetMetadataRepository;

    protected ContentCache $contentCache;

    public function __construct(
        AssetMetadataRepository $assetMetadataRepository,
        ContentCache $contentCache
    ) {
        $this->assetMetadataRepository = $assetMetadataRepository;
        $this->contentCache = $contentCache;
    }

    public function whenAssetWasCreated(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->assetMetadataRepository->add($asset, new AssetMetadata(new \DateTimeImmutable()));
        }
    }

    public function whenAssetWasUpdated(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->contentCache->flushByTag('Hoerbiger.Shared:Asset');
        }
    }

    public function whenAssetWasRemoved(AssetInterface $asset): void
    {
        if ($asset instanceof Document) {
            $this->assetMetadataRepository->remove($asset);
        }
    }
}
