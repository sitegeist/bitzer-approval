<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Service\ThumbnailService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\Kaleidoscope\Domain\AssetImageSource;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;
use Sitegeist\Kaleidoscope\EelHelpers\DummyImageSourceHelper;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

final class ImageSourceFactory extends AbstractComponentPresentationObjectFactory
{
    private ThumbnailService $thumbnailService;
    private ImageRepository $imageRepository;

    private PersistenceManagerInterface $persistenceManager;

    public function __construct(
        ThumbnailService $thumbnailService,
        ImageRepository $imageRepository,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->thumbnailService = $thumbnailService;
        $this->imageRepository = $imageRepository;
        $this->persistenceManager = $persistenceManager;

    }

    public function forDocument(
        Document $document
    ): ?ImageSourceHelperInterface {
        try {
            $thumbnail = $this->thumbnailService->getThumbnail(
                $document,
                $this->thumbnailService->getThumbnailConfigurationForPreset(PresetIdentifier::IDENTIFIER_PDF->value)
            );
        } catch (\Exception $exception) {
            $thumbnail = null;
        }

        if ($thumbnail) {
            $image = $this->imageRepository->findOneByResourceSha1($thumbnail->getResource()->getSha1());

            if (!$image instanceof Image) {
                $image = new Image($thumbnail->getResource());
                $this->imageRepository->add($image);
                $this->persistenceManager->persistAll();
            }
            return new AssetImageSource($image);
        }
        return null;
    }

    public function forImageMixin(
        TraversableNodeInterface $node,
        bool $inBackend,
        bool $hasFallbackInBackend = true
    ): ?ImageSourceHelperInterface {
        return $this->forImageProperty(
            $node,
            $inBackend,
            'image',
            $hasFallbackInBackend
        );
    }

    public function forPreviewMixin(
        TraversableNodeInterface $node,
        bool $inBackend,
        bool $hasFallbackInBackend = true
    ): ?ImageSourceHelperInterface
    {
        return $this->forImageProperty($node, $inBackend, 'preview__image', false)
            ?: $this->forImageMixin($node, $inBackend, $hasFallbackInBackend);
    }

    public function forImageProperty(
        TraversableNodeInterface $node,
        bool $inBackend,
        string $propertyPrefix,
        bool $hasFallbackInBackend = true
    ): ?ImageSourceHelperInterface {
        $imageSource = null;

        if ($node->getProperty($propertyPrefix)) {
            $imageSource = new AssetImageSourceHelper($node->getProperty($propertyPrefix));
            $imageSource->setAlt($node->getProperty($propertyPrefix.'__alt'));
            $imageSource->setTitle($node->getProperty($propertyPrefix.'__title'));
        } elseif ($inBackend && $hasFallbackInBackend) {
            $imageSource = new DummyImageSourceHelper((string)$this->uriService->getDummyImageBaseUri());
            $imageSource = $this->adjustDummyImageSource($imageSource, $node->getNodeType());
        }

        return $imageSource;
    }

    private function adjustDummyImageSource(
        DummyImageSourceHelper $imageSource,
        NodeType $nodeType
    ): DummyImageSourceHelper {
        switch ($nodeType->getConfiguration('properties.image.ui.inspector.editorOptions.crop.aspectRatio.locked')) {
            case ['width' => 1, 'height' => 1]:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->withWidth(1080)
                    ->withHeight(1080);
                break;
            case ['width' => 3, 'height' => 1]:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->withWidth(1920)
                    ->withHeight(640);
                break;
            case ['width' => 3, 'height' => 4]:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->withWidth(810)
                    ->withHeight(1080);
                break;
            case null:
            default:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->withWidth(1920)
                    ->withHeight(1080);
        }

        return $imageSource;
    }
}
