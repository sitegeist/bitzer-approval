<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview\ArticleOverviewFactory;
use Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Stage\StageFactory as SuccessStoriesStageFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{

    public function __construct(
        private readonly ArticleOverviewFactory $articleOverviewFactory,
        private readonly StageFactory $stageFactory,
        private readonly SuccessStoriesStageFactory $successStoriesStageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly LinkFactory $linkFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
    ) {
    }

    public function forMagazine(TraversableNodeInterface $site, TraversableNodeInterface $magazine, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($magazine, $subgraph, $inBackend);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($magazine, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $magazine,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULAR,
                    new CacheSegment(
                        $this->articleOverviewFactory->forMagazine($magazine),
                        'Hoerbiger.HoerbigerCom.SuccessStories:CacheSegment.SuccessStoryList'
                    )
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity),
            true
        );

    }

    public function forSuccessStory(TraversableNodeInterface $site, TraversableNodeInterface $successStory, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->successStoriesStageFactory->forSuccessStoryIntroduction($successStory, $inBackend);

        return $this->forDocumentWithIntroduction($site, $successStory, $stage, $subgraph, $inBackend);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $introSection, ContentContext $subgraph, bool $inBackend): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($document, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::VARIANT_REGULAR,
                        array_map(
                            fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARSTAGEAFTERBREADCRUMB,
                    $introSection
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity),
            true
        );
    }
}
