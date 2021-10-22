<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

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

    public function forApplicationWorlds(TraversableNodeInterface $node, NodeTypeName $targetNodeTypeName, bool $inBackend): Slidable
    {
        $targetNodes = $node->findChildNodes(
            new NodeTypeConstraints(
                false,
                [$targetNodeTypeName]
            ));

        $linkFactory = new LinkFactory();

        return new Slidable(
            Collection::fromNodes($targetNodes, function($targetNode) use ($inBackend, $linkFactory) {
                $link = $linkFactory->forLinkToDocument($targetNode);

                /** @var SlotInterface $stage */
                $stage = $this->stageFactory->forApplicationWorldSlideStage($targetNode, $inBackend);

                return $link->asContainer()->withContent($stage);
            }),
            Collection::fromNodes($targetNodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'preview__icon');

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
