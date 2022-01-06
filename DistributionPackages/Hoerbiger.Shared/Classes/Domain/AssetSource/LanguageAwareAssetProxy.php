<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\ProvidesOriginalUriInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceInterface;
use Neos\Media\Domain\Model\ImageInterface;
use Psr\Http\Message\UriInterface;

final class LanguageAwareAssetProxy implements AssetProxyInterface, ProvidesOriginalUriInterface
{
    private LanguageAwareAssetSource $assetSource;

    private Asset $asset;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    public function __construct(AssetInterface $asset, LanguageAwareAssetSource $assetSource)
    {
        if (!$asset instanceof Asset) {
            throw new \RuntimeException(
                sprintf(
                    '%s currently only support the concrete Asset implementation
                        because several methods are not part of the AssetInterface yet.',
                    __CLASS__
                ),
                1524128585
            );
        }

        $this->asset = $asset;
        $this->assetSource = $assetSource;
    }

    public function getAssetSource(): AssetSourceInterface
    {
        return $this->assetSource;
    }

    public function getIdentifier(): string
    {
        return $this->asset->getIdentifier();
    }

    public function getLabel(): string
    {
        if (empty($this->asset->getTitle())) {
            return $this->asset->getResource()->getFilename();
        }
        return $this->asset->getTitle();
    }

    public function getFilename(): string
    {
        return $this->asset->getResource()->getFilename();
    }

    public function getLastModified(): \DateTimeInterface
    {
        return $this->asset->getLastModified();
    }

    public function getFileSize(): int
    {
        return (int)$this->asset->getResource()->getFileSize();
    }

    public function getMediaType(): string
    {
        return $this->asset->getMediaType() ?? 'application/octet-stream';
    }

    public function getWidthInPixels(): ?int
    {
        if ($this->asset instanceof ImageInterface) {
            return $this->asset->getWidth() ?? null;
        }

        return 0;
    }

    public function getHeightInPixels(): ?int
    {
        if ($this->asset instanceof ImageInterface) {
            return $this->asset->getHeight() ?? 0;
        }

        return 0;
    }

    public function getAsset(): AssetInterface
    {
        return $this->asset;
    }

    public function getThumbnailUri(): ?UriInterface
    {
        return $this->assetSource->getThumbnailUriForAsset($this->asset);
    }

    public function getPreviewUri(): ?UriInterface
    {
        return $this->assetSource->getPreviewUriForAsset($this->asset);
    }

    public function getOriginalUri(): ?UriInterface
    {
        $uriString = $this->resourceManager->getPublicPersistentResourceUri($this->asset->getResource());

        return is_string($uriString)
            ? new Uri($uriString)
            : null;
    }

    /**
     * @return resource|bool
     */
    public function getImportStream()
    {
        return $this->asset->getResource()->getStream();
    }

    public function getLocalAssetIdentifier(): ?string
    {
        return $this->asset->getIdentifier();
    }

    public function getLanguage(): string
    {
        return $this->assetSource->getLanguage();
    }
}
