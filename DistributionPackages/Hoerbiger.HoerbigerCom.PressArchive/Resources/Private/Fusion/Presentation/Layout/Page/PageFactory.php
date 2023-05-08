<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverviewFactory;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage\StageFactory as PressArchiveStageFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCard;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
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
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

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
                    new ContentContainer(
                        ContentContainerVariant::regular(),
                        new Stack(
                            StackVariant::sm(),
                            Collection::fromIterable(array_filter([
                                new Headline(
                                    HeadlineSize::superLarge(),
                                    HeadlineColor::text(),
                                    HeadlineType::h1(),
                                    Editable::fromNodeProperty($overviewPage, 'headline')
                                ),
                                new Separator(
                                    SeparatorVariant::fullWidthWithContent(),
                                    Value::fromString(
                                        $this->translator->translateById(
                                            'overview.filters',
                                            [],
                                            null,
                                            null,
                                            'PressReleases',
                                            'Hoerbiger.HoerbigerCom.PressArchive'
                                        )
                                        ?: 'filters'
                                    )
                                ),
                                new CacheSegment(
                                    (new PressOverviewFactory())->forOverviewPage(
                                        $overviewPage,
                                        $subgraph,
                                        $inBackend
                                    ),
                                    'Hoerbiger.HoerbigerCom.PressArchive:CacheSegment.PressReleaseList'
                                )
                            ]))
                        )
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

        $content = $pageVariant->getIsNews()
            ? new ContentContainer(
                ContentContainerVariant::pressSmall(),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            )
            : new ContentContainer(
                ContentContainerVariant::regular(),
                new ContentContainer(
                    ContentContainerVariant::press(),
                    Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
                )
            );

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph
            ),
            Collection::fromIterable(
                array_filter([
                    count($anchors) > 0
                        ? new AnchorNavigation(
                            AnchorNavigationVariant::regular(),
                            array_map(
                                fn(Anchor $anchor): Link
                                    => (new LinkFactory())->forAnchor($anchor),
                                $anchors
                            )
                        )
                        : null,
                    new ContentContainer(
                        ContentContainerVariant::stackStage(),
                        $stage
                    ),
                    ! $pageVariant->getIsNews()
                        ? new ContentContainer(
                            ContentContainerVariant::regularX(),
                            new ContentContainer(
                                ContentContainerVariant::press(),
                                new ContentContainer(
                                    ContentContainerVariant::pressLeftBlank(),
                                    new Grid(
                                        GridVariant::twoColumnsPress(),
                                        Collection::fromIterable(array_filter([
                                            new Text(
                                                TextVariant::default(),
                                                Value::fromString(''),
                                                false
                                            ),
                                            new Separator(
                                                SeparatorVariant::bold(),
                                                null
                                            )
                                        ]))
                                    )
                                ),
                            )
                        )
                        : null,
                    $content,
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
