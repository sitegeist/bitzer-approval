<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Page;

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\HoerbigerCom\Catalog\Integration\ProductListFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
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

    private SiteFooterFactory $siteFooterFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    private ProductListFactory $productListFactory;

    public function __construct(
        SiteFooterFactory $siteFooterFactory,
        SiteHeaderFactory $siteHeaderFactory,
        ProductListFactory $productListFactory
    ) {
        $this->siteFooterFactory = $siteFooterFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
        $this->productListFactory = $productListFactory;
    }

    public function forApplicationWorld(TraversableNodeInterface $site, TraversableNodeInterface $applicationWorld, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $applicationWorld,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    (new BreadcrumbFactory())->forPage($applicationWorld, $subgraph)
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $this->slidableFactory->forApplicationWorld($applicationWorld, $inBackend)
                ),
                Content::fromNode($applicationWorld->findNamedChildNode(NodeName::fromString('main'))),
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
                                    ThumbNavigationItemType::getIsPlainText()
                                ),
                                null,
                                null,
                                $this->productListFactory->forSection($section, $subgraph, $inBackend)
                            );
                        }
                    )
                ),
            ]), $identity),
            $this->siteFooterFactory->forDocument($applicationWorld, $site)
        );
    }
}
