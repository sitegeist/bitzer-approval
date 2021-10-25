<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;
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

    public function __construct(StageFactory $stageFactory)
    {
        $this->stageFactory = $stageFactory;
    }

    public function forCatalog(TraversableNodeInterface $catalog, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($catalog, $inBackend);

        return $this->forDocumentWithIntroduction($catalog,$stage, $subgraph);
    }

    public function forProductCategory(TraversableNodeInterface $productCategory,  bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forDuoIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($productCategory,$stage, $subgraph);
    }

    public function forProduct(TraversableNodeInterface $product, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forDuoIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($product, $stage, $subgraph);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $document, SlotInterface $stage, ContentContext $subgraph): PageInterface
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
            (new SiteFooterFactory())->forSubGraph($subgraph)
        );
    }
}
