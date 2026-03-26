<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Page;

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\HoerbigerCom\Catalog\Integration\ProductListFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconColor;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;
use Hoerbiger\Shared\Presentation\Layout\TabBox\Tab\Tab;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBox;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Neos\Flow\Annotations as Flow;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly ProductListFactory $productListFactory,
        private readonly StageFactory $stageFactory,
        private readonly SlidableFactory $slidableFactory,
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly FigureFactory $figureFactory,
        private readonly LinkFactory $linkFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
    ) {
    }

    public function forApplicationWorld(TraversableNodeInterface $site, TraversableNodeInterface $applicationWorld, bool $inBackend, ContentContext $subgraph): Page
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        $sections = $applicationWorld->findNamedChildNode(NodeName::fromString('sections'))->findChildNodes();
        $stage = $sections->count() > 0
            ? $this->slidableFactory->forApplicationWorld($applicationWorld, $inBackend)
            : $this->stageFactory->forApplicationWorld($applicationWorld, $inBackend);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($applicationWorld);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $applicationWorld,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $this->breadcrumbFactory->forPage($applicationWorld, $subgraph)
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_STAGEAFTERBREADCRUMB,
                    $stage ?: Value::fromString('')
                ),
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::VARIANT_REGULAR,
                        array_map(
                            fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                Content::fromNode($applicationWorld->findNamedChildNode(NodeName::fromString('main'))),
                new CacheSegment(
                    new TabBox(
                        TabBoxVariant::VARIANT_HORIZONTAL,
                        null,
                        Collection::fromIterable(
                            $applicationWorld->findNamedChildNode(NodeName::fromString('sections'))->findChildNodes(),
                            function (TraversableNodeInterface $section) use($subgraph, $inBackend): Tab {
                                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($section, $inBackend, 'tab__image');
                                return new Tab(
                                    new ThumbNavigationItem(
                                        $thumbnailImageSource
                                            ? $this->figureFactory->forSlideThumbnailIcon($thumbnailImageSource)
                                            : null,
                                        $section->getLabel(),
                                        ThumbNavigationItemType::TYPE_PLAINTEXT
                                    ),
                                    null,
                                    new Icon('shared', 'chevronDown', IconSize::SIZE_SIZE_1_5, IconColor::COLOR_CURRENT),
                                    $this->productListFactory->forSection($section, $subgraph, $inBackend)
                                );
                            }
                        )
                    ),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductGroup'
                ),
                Content::fromNode($applicationWorld->findNamedChildNode(NodeName::fromString('footer'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $this->breadcrumbFactory->forPage($applicationWorld, $subgraph)
                )
            ]), $identity),
            true
        );
    }
}
