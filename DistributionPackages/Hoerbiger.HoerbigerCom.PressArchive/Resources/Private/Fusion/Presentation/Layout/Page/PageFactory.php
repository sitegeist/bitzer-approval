<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverviewFactory;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage\StageFactory as PressArchiveStageFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{


    public function __construct(
        private readonly PressArchiveStageFactory $pressArchiveStageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly LinkFactory $linkFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
        private readonly CsvPOTranslator $csvPOTranslator,
    ) {
    }

    public function  forPressArchive(TraversableNodeInterface $site, TraversableNodeInterface $overviewPage, ContentContext $subgraph, bool $inBackend): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($overviewPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $overviewPage,
                $site,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(
                array_filter([
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_REGULAR,
                        new Stack(
                            StackVariant::VARIANT_SM,
                            Collection::fromIterable(array_filter([
                                new Headline(
                                    HeadlineSize::SIZE_SUPER_LARGE,
                                    HeadlineColor::COLOR_TEXT,
                                    HeadlineType::TYPE_H1,
                                    Editable::fromNodeProperty($overviewPage, 'headline')
                                ),
                                new Separator(
                                    SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                                    Value::fromString(
                                        $this->csvPOTranslator->getLabelFromTranslationResource(
                                            'Hoerbiger.HoerbigerCom.PressArchive',
                                            'PressReleases',
                                            'overview.filters'
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
                        ContentContainerVariant::VARIANT_REGULARX,
                        $breadcrumb
                    ),
                ]),
                $identity
            )

        );
    }

    public function forPressRelease(TraversableNodeInterface $site, Node $pressRelease, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchiveStageFactory->forPressReleaseIntroduction($pressRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $pressRelease, $subgraph, $stage, $inBackend);
    }

    public function forYearbookRelease(TraversableNodeInterface $site, Node $yearbookRelease, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchiveStageFactory->forPressReleaseIntroduction($yearbookRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $yearbookRelease, $subgraph, $stage, $inBackend);
    }

    public function forHannsRelease(TraversableNodeInterface $site, Node $hannsRelease, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchiveStageFactory->forPressReleaseIntroduction($hannsRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $hannsRelease, $subgraph, $stage, $inBackend);
    }

    public function forYearbookOverview(TraversableNodeInterface $site, Node $yearbookRelease, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchiveStageFactory->forPressReleaseOverview($yearbookRelease, $inBackend);

        return $this->forOverviewDocumentWithIntroduction($site, $yearbookRelease, $subgraph, $stage, $inBackend);
    }

    public function forHannsOverview(TraversableNodeInterface $site, Node $hannsRelease, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->pressArchiveStageFactory->forPressReleaseOverview($hannsRelease, $inBackend);

        return $this->forOverviewDocumentWithIntroduction($site, $hannsRelease, $subgraph, $stage, $inBackend);
    }

    public function forDocumentWithIntroduction(
        TraversableNodeInterface $site,
        Node $document,
        ContentContext $subgraph,
        SlotInterface $stage,
        bool $inBackend
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($document, $subgraph);

        $pageVariant = PressVariant::tryFromNode($document);

        $content = $pageVariant === PressVariant::VARIANT_NEWS
            ? new ContentContainer(
                ContentContainerVariant::VARIANT_PRESS_SMALL,
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            )
            : new ContentContainer(
                ContentContainerVariant::VARIANT_REGULAR,
                new ContentContainer(
                    ContentContainerVariant::VARIANT_PRESS,
                    Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
                )
            );

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph,
                $inBackend
            ),
            Collection::fromIterable(
                array_filter([
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
                        ContentContainerVariant::VARIANT_STACKSTAGE,
                        $stage
                    ),
                    $pageVariant !== PressVariant::VARIANT_NEWS
                        ? new ContentContainer(
                            ContentContainerVariant::VARIANT_REGULARX,
                            new ContentContainer(
                                ContentContainerVariant::VARIANT_PRESS,
                                new ContentContainer(
                                    ContentContainerVariant::VARIANT_PRESS_LEFT_BLANK,
                                    new Grid(
                                        GridVariant::VARIANT_TWOCOLUMNSPRESS,
                                        Collection::fromIterable(array_filter([
                                            new Text(
                                                TextVariant::VARIANT_DEFAULT,
                                                Value::fromString(''),
                                                false
                                            ),
                                            new Separator(
                                                SeparatorVariant::VARIANT_BOLD,
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
                        ContentContainerVariant::VARIANT_REGULARX,
                        $breadcrumb
                    ),
                ]),
                $identity
            )
        );
    }

    public function forOverviewDocumentWithIntroduction(
        TraversableNodeInterface $site,
        Node $document,
        ContentContext $subgraph,
        SlotInterface $stage,
        bool $inBackend
    ): Page {
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
                $inBackend
            ),
            Collection::fromIterable(
                array_filter([
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
                        ContentContainerVariant::VARIANT_STACKSTAGE,
                        $stage
                    ),

                    Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),

                    new ContentContainer(
                        ContentContainerVariant::VARIANT_REGULARX,
                        $breadcrumb
                    ),
                ]),
                $identity
            )
        );
    }
}
