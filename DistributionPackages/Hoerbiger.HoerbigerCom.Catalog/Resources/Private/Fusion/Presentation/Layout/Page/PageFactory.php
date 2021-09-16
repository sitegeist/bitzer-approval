<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forCatalog(TraversableNodeInterface $catalog): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($catalog->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forProductCategory(TraversableNodeInterface $productCatalog): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($productCatalog->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forProduct(TraversableNodeInterface $product): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($product->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }
}
