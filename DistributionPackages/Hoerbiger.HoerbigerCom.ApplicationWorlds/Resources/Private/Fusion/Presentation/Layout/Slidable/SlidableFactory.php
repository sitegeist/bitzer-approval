<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Doctrine\DBAL\Types\Type;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\IconNavigationItem\IconNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;
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
    ) {
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
                $link = $linkFactory->forLinkToDocument($targetNode, null);
                $stage = $this->stageFactory->forSliderPreviewMixin($targetNode, $inBackend);

                return $link->asContainer()->withContent($stage);
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');

                return new ThumbNavigationItem(
                    $thumbnailImageSource
                        ? $this->figureFactory->forSlideThumbnailIcon($thumbnailImageSource)
                        : null,
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                    ThumbNavigationItemType::default()


                );
            })
        );
    }

    public function forApplicationWorld(TraversableNodeInterface $applicationWorld, bool $inBackend): Slidable
    {
        $sections = $applicationWorld->findNamedChildNode(NodeName::fromString('sections'))->findChildNodes();

        return new Slidable(
            Collection::fromNodes($sections, function($targetNode) use ($inBackend, $applicationWorld): SlotInterface {
                return $this->stageFactory->forSection($targetNode, $applicationWorld, $inBackend);
            }),
            Collection::fromNodes($sections, function($section): IconNavigationItem {
                return new IconNavigationItem(
                    Icon::fromStampedeDataSourceValue(
                        $section->getProperty('icon') ?: 'shared:search',
                        IconSize::size_2()
                    ),
                    $section->getLabel()
                );
            }),
            true
        );
    }
}
