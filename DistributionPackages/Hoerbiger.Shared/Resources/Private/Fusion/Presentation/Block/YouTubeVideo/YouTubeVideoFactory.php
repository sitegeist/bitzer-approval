<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\YouTubeVideo;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\VideoIdentifier;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class YouTubeVideoFactory extends AbstractComponentPresentationObjectFactory
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

    public function forYoutubeVideo(TraversableNodeInterface $youtubeVideoNode, bool $inBackend, ?Theme $theme): ?YouTubeVideo
    {
        $videoIdentifier = $youtubeVideoNode->getProperty('videoIdentifier');
        $imageSource = $this->imageSourceFactory->forImageMixin($youtubeVideoNode, $inBackend);

        if ($videoIdentifier instanceof VideoIdentifier) {
            return new YouTubeVideo(
                YouTubeVideoAspectRatio::from($videoIdentifier->aspectRatio->value),
                $videoIdentifier->renderUri(),
                $imageSource
                    ? $this->figureFactory->forVideoPoster($imageSource)
                    : null,
                new Icon('shared', 'pdf')
            );
        }

        return null;
    }
}
