<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forHomePage(TraversableNodeInterface $homepage, NodeInterface $siteNode, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        $targetNodeTypeName = NodeTypeName::fromString('Hoerbiger.HoerbigerCom:Tag.HomePageStageContent');

        $stage = (new SlidableFactory())->forApplicationWorlds($homepage, $targetNodeTypeName, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($homepage, $siteNode, $stage);
    }

    public function forWebPage(TraversableNodeInterface $webPage, NodeInterface $siteNode, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = (new StageFactory())->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($webPage, $siteNode, $stage);
    }

    public function forNotFoundPage(TraversableNodeInterface $notFoundPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
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
