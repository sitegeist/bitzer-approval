<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionFactory;
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
    public function forHomePage(TraversableNodeInterface $homepage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forWebPage(TraversableNodeInterface $webPage, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $introSection */
        $introSection = (new IntroSectionFactory())->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($webPage, $introSection);
    }

    public function forNotFoundPage(TraversableNodeInterface $notFoundPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
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
