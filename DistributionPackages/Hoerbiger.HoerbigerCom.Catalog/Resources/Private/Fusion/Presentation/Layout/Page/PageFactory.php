<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Integration\ProductListFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Stage\StageFactory as CatalogStageFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Neos\Flow\Annotations as Flow;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly StageFactory $stageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly ProductListFactory $productListFactory,
        private readonly CatalogStageFactory $catalogStageFactory,
        private readonly LinkFactory $linkFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
    ) {
    }

    public function forCatalog(TraversableNodeInterface $site, TraversableNodeInterface $catalog, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($catalog, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $catalog, $stage, $subgraph, $inBackend);
    }

    public function forProductCategory(TraversableNodeInterface $site, TraversableNodeInterface $productCategory,  bool $inBackend, ContentContext $subgraph): Page
    {
        $stage = $this->catalogStageFactory->forProductAndCategoryIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($site, $productCategory,$stage, $subgraph, $inBackend);
    }

    public function forProduct(TraversableNodeInterface $site, TraversableNodeInterface $product, bool $inBackend, ContentContext $subgraph): Page
    {
        $stage = $this->catalogStageFactory->forProductAndCategoryIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($site, $product, $stage, $subgraph, $inBackend);
    }

    public function forIndustrySolutions(TraversableNodeInterface $site, TraversableNodeInterface $industrySolutions, bool $inBackend, ContentContext $subgraph): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($industrySolutions, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($industrySolutions, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($industrySolutions);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $industrySolutions,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
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
                new CacheSegment(
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_REGULAR,
                        new Grid(
                            GridVariant::VARIANT_THREECOLUMNSGAP,
                            Collection::fromIterable((new CardFactory())->forIndustrySolutions($industrySolutions), $identity)
                        )
                    ),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.IndustrySolutionList'
                ),
                Content::fromNode($industrySolutions->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forIndustrySolution(TraversableNodeInterface $site, TraversableNodeInterface $industrySolution, bool $inBackend, ContentContext $subgraph): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($industrySolution, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($industrySolution, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($industrySolution);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $industrySolution,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
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
                Content::fromNode($industrySolution->findNamedChildNode(NodeName::fromString('main'))),
                new CacheSegment(
                    $this->productListFactory->forIndustrySolution($industrySolution, $subgraph, $inBackend),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductList'
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forDocumentWithIntroduction(
        TraversableNodeInterface $site,
        TraversableNodeInterface $document,
        SlotInterface $stage,
        ContentContext $subgraph,
        bool $inBackend
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($document, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::VARIANT_REGULAR,
                        array_map(
                            fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }
}
