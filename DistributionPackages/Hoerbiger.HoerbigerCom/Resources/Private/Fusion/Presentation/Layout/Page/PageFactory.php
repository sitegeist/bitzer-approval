<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm\SearchFormFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm\ContactFormFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconColor;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainer;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainerFactory;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly StageFactory $stageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly LinkFactory $linkFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
        private readonly CsvPOTranslator $csvPOTranslator,
    ) {
    }

    public function forCareerPage(TraversableNodeInterface $careerPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($careerPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $careerPage, $stage, $subgraph, $inBackend);
    }

    public function forPrivacyPage(TraversableNodeInterface $privacyPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($privacyPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $privacyPage, $stage, $subgraph, $inBackend);
    }

    public function forProtectedArea(
        TraversableNodeInterface $protectedArea,
        TraversableNodeInterface $siteNode,
        bool $inBackend,
        ContentContext $subgraph
    ): Page {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($protectedArea, $subgraph, $inBackend);

        return $this->forPageWithoutFooter($siteNode, $protectedArea, null, $stage, $subgraph, $inBackend);
    }

    public function forProtectedPage(
        TraversableNodeInterface $protectedPage,
        TraversableNodeInterface $siteNode,
        bool $inBackend,
        ContentContext $subgraph
    ): Page {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($protectedPage, $subgraph, $inBackend);

        $protectedAreaPage = $siteNode->findNamedChildNode(NodeName::fromString('protectedarea'));


        return $this->forPageWithoutFooter(
            $siteNode,
            $protectedPage,
            $protectedAreaPage,
            $stage,
            $subgraph,
            $inBackend
        );
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend, ContentContext $subgraph): Page
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        $videoStage = $this->stageFactory->forVideoStageIntroduction($homepage, $inBackend);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($homepage);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $homepage,
                $homepage,
                $subgraph,
                $inBackend,
                null,
            ),
            Collection::fromIterable(array_filter([
                // @TODO REMOVE old stage
//                $videoStage
//                    ? new ContentContainer(
//                        ContentContainerVariant::blank(),
//                        $videoStage
//                    )
//                    : null,
                Content::fromNode(
                    $homepage->findNamedChildNode(NodeName::fromString('stage')),
                    'Hoerbiger.HoerbigerCom:ContentSlot'
                ),
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
                    ContentContainerVariant::VARIANT_REGULAR,
                    new AnimatedContentContainer(
                        AnimatedContentContainerVariant::VARIANT_FADE_AND_TRANSLATE,
                        new Headline(
                            HeadlineSize::SIZE_MEDIUM_ITALIC,
                            HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                            HeadlineType::TYPE_H1,
                            Editable::fromNodeProperty($homepage, 'headline')
                        ),
                        AnimatedContentContainerFactory::getClassForVisibleContainer(
                            AnimatedContentContainerVariant::VARIANT_FADE_AND_TRANSLATE
                        ),
                        AnimatedContentContainerFactory::getClassForInvisibleContainer(
                            AnimatedContentContainerVariant::VARIANT_FADE_AND_TRANSLATE
                        ),
                    )
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_SMALL_TEXT,
                    Collection::fromIterable(array_filter([
                        $homepage->getProperty('teaser_text_headline') || $inBackend
                            ? new Headline(
                                HeadlineSize::SIZE_VERY_SMALL,
                                HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                                HeadlineType::TYPE_DIV,
                                Editable::fromNodeProperty($homepage, 'teaser_text_headline')
                            )
                            : null,
                        new Text(
                            TextVariant::VARIANT_NEUTRAL_CONTRAST_FIRA_PRO,
                            Editable::fromNodeProperty($homepage, "text"),
                            false
                        )
                    ]))
                ),
                Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            true
        );
    }

    public function forSearchResultsPage(TraversableNodeInterface $searchResultsPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        $imageSource = $this->imageSourceFactory->forImageMixin($searchResultsPage, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($searchResultsPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($searchResultsPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $searchResultsPage,
                $siteNode,
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
                    (new SearchFormFactory())->create($subgraph)
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity),
            true
        );
    }

    public function forWebPage(TraversableNodeInterface $site, TraversableNodeInterface $webPage, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($webPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $webPage, $stage, $subgraph, $inBackend);
    }

    public function forCareerRootPage(TraversableNodeInterface $site, TraversableNodeInterface $careerRootPage, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($careerRootPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $careerRootPage, $stage, $subgraph, $inBackend);
    }

    public function forPeopleAndCultureWebPage(TraversableNodeInterface $careerPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($careerPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $careerPage, $stage, $subgraph, $inBackend);
    }

    public function forContactPage(TraversableNodeInterface $site, TraversableNodeInterface $contactPage, bool $inBackend, ContentContext $subgraph): Page
    {
        $languages = $subgraph->getDimensions()['language'];
        $language = reset($languages);
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($contactPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($contactPage, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($contactPage);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $contactPage,
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
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK,
                    (new ContactFormFactory())->create(
                        $site,
                        $language,
                        $inBackend,
                        Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('contactonsuccess')), 'Hoerbiger.HoerbigerCom:ContentSlot'),
                        Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('contactonfail')), 'Hoerbiger.HoerbigerCom:ContentSlot')
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

    public function forChroniclePage(TraversableNodeInterface $site, TraversableNodeInterface $chroniclePage, ContentContext $subgraph, bool $inBackend): Page
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($chroniclePage, $subgraph, $inBackend);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($chroniclePage, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($chroniclePage);

        $contentSection = $chroniclePage->findNamedChildNode(NodeName::fromString('content'));

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $chroniclePage,
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
                count($anchors) > 0
                    ? new AnchorNavigation(
                    AnchorNavigationVariant::VARIANT_REGULAR,
                    array_map(
                        fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                        $anchors
                    )
                )
                    : null,

                ($contentSection->findChildNodes()->count() > 0)
                    ? new ContentContainer(
                        ContentContainerVariant::VARIANT_REGULAR,
                        Content::fromNode($contentSection),
                    )
                    : null,
                Content::fromNode($chroniclePage->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity),
            true
        );
    }

    public function forNotFoundPage(TraversableNodeInterface $site, TraversableNodeInterface $notFoundPage, ContentContext $subgraph, bool $inBackend): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($notFoundPage, $subgraph, $inBackend);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($notFoundPage);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $notFoundPage,
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
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            true
        );
    }

    private function forPageWithoutFooter(
        TraversableNodeInterface $site,
        TraversableNodeInterface $document,
        ?TraversableNodeInterface $overviewPageUri,
        SlotInterface $stage,
        ContentContext $subgraph,
        bool $inBackend,
    ): Page
    {
        $labels = $this->csvPOTranslator->getLabels(Button::class);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($document, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($document);

        $overviewPageUri = $overviewPageUri ? $this->uriService->getNodeUri($overviewPageUri) : null;

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
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
                count($anchors) > 0
                    ? new AnchorNavigation(
                    AnchorNavigationVariant::VARIANT_REGULAR,
                    array_map(
                        fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                        $anchors
                    )
                )
                    : null,
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    new Button(
                        ButtonType::TYPE_LINK,
                        ButtonVariant::VARIANT_CONTRAST,
                        new Grid(
                            GridVariant::VARIANT_ICONLEFT,
                            Collection::fromIterable(array_filter([
                                new Icon(
                                    'shared',
                                    'chevronLeft',
                                    IconSize::SIZE_SIZE_0_75,
                                    IconColor::COLOR_CURRENT
                                ),
                                Value::fromString($labels['label.backToOverview']),
                            ]))
                        ),
                        $overviewPageUri,
                        '_self',
                        null
                    ),
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity),
            false
        );
    }

    public function forDocumentWithIntroduction(
        TraversableNodeInterface $site,
        TraversableNodeInterface $document,
        SlotInterface $stage,
        ContentContext $subgraph,
        bool $inBackend,
    ): Page
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
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                count($anchors) > 0
                    ? new AnchorNavigation(
                        AnchorNavigationVariant::VARIANT_REGULAR,
                        array_map(
                            fn(Anchor $anchor): Link => $this->linkFactory->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
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
