<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview\ArticleOverviewFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Stage\StageFactory as SuccessStoriesStageFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\ScrollToTop\ScrollToTop;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private ArticleOverviewFactory $articleOverviewFactory;

    private StageFactory $stageFactory;

    private SuccessStoriesStageFactory $successStoriesStageFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    public function __construct(
        ArticleOverviewFactory $articleOverviewFactory,
        StageFactory $stageFactory,
        SuccessStoriesStageFactory $successStoriesStageFactory,
        SiteHeaderFactory $siteHeaderFactory
    ) {
        $this->articleOverviewFactory = $articleOverviewFactory;
        $this->stageFactory = $stageFactory;
        $this->successStoriesStageFactory = $successStoriesStageFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
    }

    public function forMagazine(TraversableNodeInterface $site, TraversableNodeInterface $magazine, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($magazine, $subgraph, $inBackend);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($magazine, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $magazine,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::regular(),
                    new CacheSegment(
                        $this->articleOverviewFactory->forMagazine($magazine),
                        'Hoerbiger.HoerbigerCom.SuccessStories:CacheSegment.SuccessStoryList'
                    )
                )
            ]), $identity)
        );

    }

    public function forSuccessStory(TraversableNodeInterface $site, TraversableNodeInterface $successStory, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->successStoriesStageFactory->forSuccessStoryIntroduction($successStory, $inBackend);

        return $this->forDocumentWithIntroduction($site, $successStory, $stage, $subgraph);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $introSection, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($document, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::regular(),
                        array_map(
                            fn(Anchor $anchor): Link
                                => new Link(
                                    LinkVariant::label(),
                                    new Uri('#' . $anchor->getIdentifier()),
                                    LinkTarget::self(),
                                    null,
                                    Value::fromString($anchor->getLabel()),
                                    null
                            ),
                            $anchors
                        )
                    )
                    : null,
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::regularStageAfterBreadcrumb(),
                    $introSection
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity)
        );
    }
}
