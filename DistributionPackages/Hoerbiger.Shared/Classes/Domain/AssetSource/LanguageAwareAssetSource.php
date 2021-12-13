<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Http\HttpRequestHandlerInterface;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyRepositoryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceInterface;
use Neos\Media\Domain\Service\AssetService;
use Neos\Media\Domain\Service\ThumbnailService;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

/**
 * Asset source for language aware Neos native assets
 */
final class LanguageAwareAssetSource implements AssetSourceInterface
{
    /**
     * @Flow\Inject
     * @var ThumbnailService
     */
    protected $thumbnailService;

    /**
     * @Flow\Inject
     * @var AssetService
     */
    protected $assetService;

    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @Flow\InjectConfiguration(path="asyncThumbnails")
     * @var bool
     */
    protected $asyncThumbnails;

    private LanguageAwareAssetSourceIdentifier $assetSourceIdentifier;

    private ?LanguageAwareAssetProxyRepository $assetProxyRepository = null;

    private string $iconPath = '';

    private string $description = '';

    private string $label = '';

    private string $language = '';

    /**
     * @param array<string,mixed> $assetSourceOptions
     */
    public function __construct(string $assetSourceIdentifier, array $assetSourceOptions)
    {
        $this->assetSourceIdentifier = LanguageAwareAssetSourceIdentifier::fromString($assetSourceIdentifier);

        foreach ($assetSourceOptions as $optionName => $optionValue) {
            switch ($optionName) {
                case 'asyncThumbnails':
                    // If the option value is empty,
                    // preserve the default value injected from the Neos:Media:asyncThumbnails setting:
                    if (!empty($optionValue)) {
                        if (!is_bool($optionValue)) {
                            throw new \InvalidArgumentException(
                                sprintf(
                                    'Asset source option "%s" specified for language aware asset source "%s"
                                        must be either true or false. Please check your settings.',
                                    $optionName,
                                    $assetSourceIdentifier
                                ),
                                1522927471
                            );
                        }
                        $this->asyncThumbnails = $optionValue;
                    }
                    break;
                case 'icon':
                    $this->iconPath = $optionValue;
                    break;
                case 'description':
                    $this->description = $optionValue;
                    break;
                case 'label':
                    $this->label = $optionValue;
                    break;
                case 'language':
                    $this->language = $optionValue;
                    break;
                default:
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Unknown asset source option "%s" specified for language aware asset source "%s".
                                Please check your settings.',
                            $optionName,
                            $assetSourceIdentifier
                        ),
                        1513327774
                    );
            }
        }
    }

    /**
     * @param array<string,mixed> $assetSourceOptions
     */
    public static function createFromConfiguration(
        string $assetSourceIdentifier,
        array $assetSourceOptions
    ): self {
        return new self($assetSourceIdentifier, $assetSourceOptions);
    }

    public function getIdentifier(): string
    {
        return $this->assetSourceIdentifier->toString();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIconUri(): string
    {
        return $this->resourceManager->getPublicPackageResourceUriByPath($this->iconPath);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getAssetProxyRepository(): AssetProxyRepositoryInterface
    {
        if ($this->assetProxyRepository === null) {
            $this->assetProxyRepository = new LanguageAwareAssetProxyRepository($this);
        }

        return $this->assetProxyRepository;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getThumbnailUriForAsset(AssetInterface $asset): ?UriInterface
    {
        $actionRequest = ($this->asyncThumbnails ? $this->createActionRequest() : null);
        $thumbnailConfiguration = $this->thumbnailService->getThumbnailConfigurationForPreset(
            'Neos.Media.Browser:Thumbnail',
            ($actionRequest !== null)
        );
        $thumbnailData = $this->assetService->getThumbnailUriAndSizeForAsset(
            $asset,
            $thumbnailConfiguration,
            $actionRequest
        );

        return isset($thumbnailData['src']) ? new Uri($thumbnailData['src']) : null;
    }

    public function getPreviewUriForAsset(AssetInterface $asset): ?UriInterface
    {
        $actionRequest = ($this->asyncThumbnails ? $this->createActionRequest() : null);
        $thumbnailConfiguration = $this->thumbnailService->getThumbnailConfigurationForPreset(
            'Neos.Media.Browser:Preview',
            ($actionRequest !== null)
        );
        $thumbnailData = $this->assetService->getThumbnailUriAndSizeForAsset(
            $asset,
            $thumbnailConfiguration,
            $actionRequest
        );

        return isset($thumbnailData['src']) ? new Uri($thumbnailData['src']) : null;
    }

    private function createActionRequest(): ?ActionRequest
    {
        $requestHandler = $this->bootstrap->getActiveRequestHandler();
        if ($requestHandler instanceof HttpRequestHandlerInterface) {
            return ActionRequest::fromHttpRequest($requestHandler->getHttpRequest());
        }

        return null;
    }
}
