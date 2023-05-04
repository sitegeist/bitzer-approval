<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Page;

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\HoerbigerCom\Catalog\Integration\ProductListFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
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
use Hoerbiger\Shared\Presentation\Block\ScrollToTop\ScrollToTop;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;
use Hoerbiger\Shared\Presentation\Layout\TabBox\Tab\Tab;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBox;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var SlidableFactory
     */
    protected $slidableFactory;

    /**
     * @Flow\Inject
     * @var ImageSourceFactory
     */
    protected $imageSourceFactory;

    /**
     * @Flow\Inject
     * @var FigureFactory
     */
    protected $figureFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    private ProductListFactory $productListFactory;

    private StageFactory $stageFactory;

    public function __construct(
        SiteHeaderFactory $siteHeaderFactory,
        ProductListFactory $productListFactory,
        StageFactory $stageFactory
    ) {
        $this->siteHeaderFactory = $siteHeaderFactory;
        $this->productListFactory = $productListFactory;
        $this->stageFactory = $stageFactory;
    }

    public function forApplicationWorld(TraversableNodeInterface $site, TraversableNodeInterface $applicationWorld, bool $inBackend, ContentContext $subgraph): PageInterface
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
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::regular(),
                        array_map(
                            fn(Anchor $anchor): Link
                                => (new LinkFactory())->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    (new BreadcrumbFactory())->forPage($applicationWorld, $subgraph)
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage ?: Value::fromString('')
                ),
                Content::fromNode($applicationWorld->findNamedChildNode(NodeName::fromString('main'))),
                new CacheSegment(
                    new TabBox(
                        TabBoxVariant::horizontal(),
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
                                        ThumbNavigationItemType::plainText()
                                    ),
                                    null,
                                    new Icon('shared', 'chevronDown', IconSize::size_1_5(), IconColor::current()),
                                    $this->productListFactory->forSection($section, $subgraph, $inBackend)
                                );
                            }
                        )
                    ),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductGroup'
                ),
                Content::fromNode($applicationWorld->findNamedChildNode(NodeName::fromString('footer'))),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    (new BreadcrumbFactory())->forPage($applicationWorld, $subgraph)
                )
            ]), $identity)
        );
    }
}
