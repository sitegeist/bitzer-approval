<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview\ArticleOverviewFactory;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private ArticleOverviewFactory $articleOverviewFactory;

    private StageFactory $stageFactory;

    public function __construct(ArticleOverviewFactory $articleOverviewFactory, StageFactory $stageFactory)
    {
        $this->articleOverviewFactory = $articleOverviewFactory;
        $this->stageFactory = $stageFactory;
    }

    public function forMagazine(TraversableNodeInterface $magazine, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($magazine, $inBackend);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    Collection::fromIterable([
                        $stage,
                        new CacheSegment(
                            $this->articleOverviewFactory->forMagazine($magazine),
                            'Hoerbiger.HoerbigerCom.SuccessStories:CacheSegment.SuccessStoryList'
                        )
                    ], $identity)
                ),
            ]), $identity),
            new SiteFooter()
        );

    }

    public function forSuccessStory(TraversableNodeInterface $successStory, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($successStory, $inBackend);

        return $this->forDocumentWithIntroduction($successStory, $stage);
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
