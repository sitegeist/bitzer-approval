<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Catalog\Integration\ProductListFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Stage\StageFactory as CatalogStageFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\ScrollToTop\ScrollToTop;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var CatalogStageFactory
     */
    protected $catalogstageFactory;

    private StageFactory $stageFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    private ProductListFactory $productListFactory;

    public function __construct(
        StageFactory $stageFactory,
        SiteHeaderFactory $siteHeaderFactory,
        ProductListFactory $productListFactory
    ) {
        $this->stageFactory = $stageFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
        $this->productListFactory = $productListFactory;
    }

    public function forCatalog(TraversableNodeInterface $site, TraversableNodeInterface $catalog, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($catalog, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $catalog, $stage, $subgraph);
    }

    public function forProductCategory(TraversableNodeInterface $site, TraversableNodeInterface $productCategory,  bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $stage = $this->catalogstageFactory->forProductAndCategoryIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($site, $productCategory,$stage, $subgraph);
    }

    public function forProduct(TraversableNodeInterface $site, TraversableNodeInterface $product, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $stage = $this->catalogstageFactory->forProductAndCategoryIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($site, $product, $stage, $subgraph);
    }

    public function forIndustrySolutions(TraversableNodeInterface $site, TraversableNodeInterface $industrySolutions, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($industrySolutions, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($industrySolutions, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($industrySolutions);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $industrySolutions,
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
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                new CacheSegment(
                    new ContentContainer(
                        ContentContainerVariant::regular(),
                        new Grid(
                            GridVariant::threeColumnsGap(),
                            Collection::fromIterable((new CardFactory())->forIndustrySolutions($industrySolutions), $identity)
                        )
                    ),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.IndustrySolutionList'
                ),
                Content::fromNode($industrySolutions->findNamedChildNode(NodeName::fromString('main'))),
            ]), $identity)
        );
    }

    public function forIndustrySolution(TraversableNodeInterface $site, TraversableNodeInterface $industrySolution, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($industrySolution, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($industrySolution, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($industrySolution);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $industrySolution,
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
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                Content::fromNode($industrySolution->findNamedChildNode(NodeName::fromString('main'))),
                new CacheSegment(
                    $this->productListFactory->forIndustrySolution($industrySolution, $subgraph, $inBackend),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductList'
                ),
            ]), $identity)
        );
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $stage, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($document, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
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
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity)
        );
    }
}
