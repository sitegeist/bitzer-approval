<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class VideoFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    public function forVideo(TraversableNodeInterface $videoNode, bool $inBackend, ?Theme $theme): Video
    {
        $asset = $videoNode->getProperty('video');
        $assetUri = $asset ? $this->uriService->getAssetUri($asset) : '';
        $imageSource = $this->imageSourceFactory->forImageMixin($videoNode, $inBackend);

        return new Video(
            (string) $assetUri,
            $imageSource
                ? $this->figureFactory->forVideoPoster($imageSource)
                : null,
            Editable::fromNodeProperty($videoNode, 'image__caption'),
            VideoVariant::tryFromTheme($theme)
        );
    }
}
