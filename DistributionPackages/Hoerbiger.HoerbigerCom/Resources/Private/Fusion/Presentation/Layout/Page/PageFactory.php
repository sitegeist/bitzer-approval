<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
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
    private SlidableFactory $slidableFactory;

    private StageFactory $stageFactory;

    private SiteFooterFactory $siteFooterFactory;

    public function __construct(
        SlidableFactory $slidableFactory,
        StageFactory $stageFactory,
        SiteFooterFactory $siteFooterFactory
    ) {
        $this->slidableFactory = $slidableFactory;
        $this->stageFactory = $stageFactory;
        $this->siteFooterFactory = $siteFooterFactory;
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $stage = $this->slidableFactory->forSliderPreviewMixins(
            $this->findChildNodesByNodeTypeFilterString($homepage, 'Hoerbiger.HoerbigerCom:Mixin.SliderPreview'),
            $inBackend
        );

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $stage
                ),
                Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            $this->siteFooterFactory->forDocument($homepage, $homepage)
        );
    }

    public function forWebPage(TraversableNodeInterface $site, TraversableNodeInterface $webPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($site, $webPage, $stage, $subgraph);
    }

    public function forChroniclePage(TraversableNodeInterface $site, TraversableNodeInterface $chroniclePage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($chroniclePage, $inBackend);

        return $this->forDocumentWithIntroduction($site, $chroniclePage, $stage, $subgraph);
    }

    public function forNotFoundPage(TraversableNodeInterface $site, TraversableNodeInterface $notFoundPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            $this->siteFooterFactory->forDocument($notFoundPage, $site)
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
