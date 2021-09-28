<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionFactory;
use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionInterface;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
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
        /** @var SlotInterface $introSection */
        $introSection = (new IntroSectionFactory())->forFullMediaIntroduction($catalog, $inBackend);

        return $this->forDocumentWithIntroduction($catalog, $introSection);
    }

    public function forProductCategory(TraversableNodeInterface $productCategory, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $introSection */
        $introSection = (new IntroSectionFactory())->forDuoIntroduction($productCategory, $inBackend);

        return $this->forDocumentWithIntroduction($productCategory, $introSection);
    }

    public function forProduct(TraversableNodeInterface $product, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $introSection */
        $introSection = (new IntroSectionFactory())->forDuoIntroduction($product, $inBackend);

        return $this->forDocumentWithIntroduction($product, $introSection);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $document, SlotInterface $introSection): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $introSection
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            new SiteFooter()
        );
    }
}
