<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageInterface;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forCatalog(TraversableNodeInterface $catalog, NodeInterface $siteNode, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = (new StageFactory())->forFullMediaIntroduction($catalog, $inBackend);

        return $this->forDocumentWithIntroduction($catalog, $siteNode, $stage);
    }

    public function forProductCategory(TraversableNodeInterface $productCategory, NodeInterface $siteNode, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = (new StageFactory())->forDuoIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($productCategory, $siteNode, $stage);
    }

    public function forProduct(TraversableNodeInterface $product, NodeInterface $siteNode, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = (new StageFactory())->forDuoIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($product, $siteNode, $stage);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $document, NodeInterface $siteNode, SlotInterface $stage): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                (new BreadcrumbFactory())->forPage($document,$siteNode),
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $stage
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            new SiteFooter()
        );
    }
}
