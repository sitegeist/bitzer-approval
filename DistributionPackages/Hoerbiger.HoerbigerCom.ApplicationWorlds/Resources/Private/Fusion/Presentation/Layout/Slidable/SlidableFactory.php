<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

final class SlidableFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        StageFactory $stageFactory,
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    )
    {
        $this->stageFactory = $stageFactory;
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $nodes
     */
    public function forSliderPreviewMixins(TraversableNodes $nodes, bool $inBackend): Slidable
    {
        $linkFactory = new LinkFactory();

        return new Slidable(
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
                $link = $linkFactory->forLinkToDocument($targetNode);
                $stage = $this->stageFactory->forSliderPreviewMixin($targetNode, $inBackend);

                return $link->asContainer()->withContent($stage);
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');

                return new ThumbNavigationItem(
                    $thumbnailImageSource
                        ? $this->figureFactory->forSlideThumbnailIcon($thumbnailImageSource)
                        : null,
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: ''
                );
            })
        );
    }
}
