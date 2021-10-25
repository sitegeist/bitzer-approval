<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
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
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private SlidableFactory $slidableFactory;

    private StageFactory $stageFactory;

    public function __construct(SlidableFactory $slidableFactory, StageFactory $stageFactory)
    {
        $this->slidableFactory = $slidableFactory;
        $this->stageFactory = $stageFactory;
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $targetNodeTypeName = NodeTypeName::fromString('Hoerbiger.HoerbigerCom:Tag.HomePageStageContent');
        $stage = $this->slidableFactory->forApplicationWorlds($homepage, $targetNodeTypeName, $inBackend);

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $stage
                ),
                Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            (new SiteFooterFactory())->forSubGraph($subgraph)
        );
    }

    public function forWebPage(TraversableNodeInterface $webPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($webPage, $stage, $subgraph);
    }

    public function forNotFoundPage(TraversableNodeInterface $notFoundPage, ContentContext $subgraph): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            (new SiteFooterFactory())->forSubGraph($subgraph)
        );
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
