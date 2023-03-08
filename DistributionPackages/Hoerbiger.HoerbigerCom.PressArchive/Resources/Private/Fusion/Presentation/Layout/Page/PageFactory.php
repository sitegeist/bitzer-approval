<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverviewFactory;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage\StageFactory as PressArchiveStageFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\ScrollToTop\ScrollToTop;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private PressArchiveStageFactory $pressArchivestageFactory;


    private SiteHeaderFactory $siteHeaderFactory;

    public function __construct(
        PressArchiveStageFactory $pressArchivestageFactory,
        SiteHeaderFactory $siteHeaderFactory
    ) {
        $this->pressArchivestageFactory = $pressArchivestageFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
    }

    public function  forPressArchive(TraversableNodeInterface $site, TraversableNodeInterface $overviewPage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($overviewPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $overviewPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(
                array_filter([
                    new ScrollToTop(),
                    new Headline(
                        HeadlineSize::superLarge(),
                        HeadlineColor::brand(),
                        HeadlineType::h1(),
                        Editable::fromNodeProperty($overviewPage, 'headline')
                    ),
                    new CacheSegment(
                        (new PressOverviewFactory())->forOverviewPage(
                            $overviewPage,
                            $inBackend
                        ),
                        'Hoerbiger.HoerbigerCom.PressArchive:CacheSegment.PressReleaseList'
                    ),
                    new ContentContainer(
                        ContentContainerVariant::regularX(),
                        $breadcrumb
                    ),
                ]),
                $identity
            )

        );
    }

    public function forPressRelease(TraversableNodeInterface $site, Node $pressRelease, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchivestageFactory->forPressReleaseIntroduction($pressRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $pressRelease, $subgraph, $stage);
    }

    public function forYearbookRelease(TraversableNodeInterface $site, Node $yearbookRelease, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchivestageFactory->forPressReleaseIntroduction($yearbookRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $yearbookRelease, $subgraph, $stage);
    }

    public function forHannsRelease(TraversableNodeInterface $site, Node $hannsRelease, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchivestageFactory->forPressReleaseIntroduction($hannsRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $hannsRelease, $subgraph, $stage);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, Node $document, ContentContext $subgraph, SlotInterface $stage): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($document, $subgraph);

        $pageVariant = PressVariant::fromNode($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph
            ),
            Collection::fromIterable(
                array_filter([
                    new ScrollToTop(),
                    new ContentContainer(
                        ContentContainerVariant::regularStageAfterBreadcrumb(),
                        $stage
                    ),
                    ! $pageVariant->getIsNews()
                        ? new ContentContainer(
                            ContentContainerVariant::regular(),
                            new Separator(SeparatorVariant::bold())
                        )
                        : null,
                    Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),
                    new ContentContainer(
                        ContentContainerVariant::regularX(),
                        $breadcrumb
                    ),
                ]),
                $identity
            )
        );
    }
}
