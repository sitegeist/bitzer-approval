<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
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
    public function forHomePage(TraversableNodeInterface $homepage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forWebPage(TraversableNodeInterface $webPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($webPage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forNotFoundPage(TraversableNodeInterface $notFoundPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }
}
