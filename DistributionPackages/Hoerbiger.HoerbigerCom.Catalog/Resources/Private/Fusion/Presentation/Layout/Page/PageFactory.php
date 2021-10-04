<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forCatalog(TraversableNodeInterface $catalog, bool $inBackend): PageInterface
    {
        return $this->forDocumentWithIntroduction($catalog, $inBackend);
    }

    public function forProductCategory(TraversableNodeInterface $productCategory, bool $inBackend): PageInterface
    {
        return $this->forDocumentWithIntroduction($productCategory, $inBackend);
    }

    public function forProduct(TraversableNodeInterface $product, bool $inBackend): PageInterface
    {
        return $this->forDocumentWithIntroduction($product, $inBackend);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $document, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                (new IntroSectionFactory())->forIntroductionMixin($document, $inBackend),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            new SiteFooter()
        );
    }
}
