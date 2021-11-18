<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{

    private StageFactory $stageFactory;

    private SiteFooterFactory $siteFooterFactory;

    public function __construct(StageFactory $stageFactory, SiteFooterFactory $siteFooterFactory)
    {
        $this->stageFactory = $stageFactory;
        $this->siteFooterFactory = $siteFooterFactory;
    }

    public function forCatalog(TraversableNodeInterface $site, TraversableNodeInterface $catalog, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($catalog, $inBackend);

        return $this->forDocumentWithIntroduction($site, $catalog, $stage, $subgraph);
    }

    public function forProductCategory(TraversableNodeInterface $site, TraversableNodeInterface $productCategory,  bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forDuoIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($site, $productCategory,$stage, $subgraph);
    }

    public function forProduct(TraversableNodeInterface $site, TraversableNodeInterface $product, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forDuoIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($site, $product, $stage, $subgraph);
    }

    public function forIndustrySolutions(TraversableNodeInterface $site, TraversableNodeInterface $industrySolutions, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($industrySolutions, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($industrySolutions, $subgraph);

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                new CacheSegment(
                    Collection::fromIterable((new CardFactory())->forIndustrySolutions($industrySolutions), $identity),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.IndustrySolutionList'
                )
            ]), $identity),
            $this->siteFooterFactory->forDocument($industrySolutions, $site)
        );
    }

    public function forIndustrySolution(TraversableNodeInterface $site, TraversableNodeInterface $industrySolution, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($industrySolution, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($industrySolution, $subgraph);

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
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
                    Collection::fromIterable((new CardFactory())->forIndustrySolutionProducts($subgraph, $industrySolution), $identity),
                    'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductList'
                )
            ]), $identity),
            $this->siteFooterFactory->forDocument($industrySolution, $site)
        );
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $stage, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($document, $subgraph);

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            $this->siteFooterFactory->forDocument($document, $site)
        );
    }
}
