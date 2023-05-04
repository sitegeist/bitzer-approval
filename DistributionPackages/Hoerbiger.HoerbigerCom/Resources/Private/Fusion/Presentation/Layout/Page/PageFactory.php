<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm\SearchFormFactory;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Domain\Sailor;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigation;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm\ContactFormFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainer;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainerFactory;
use Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer\AnimatedContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
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
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private SlidableFactory $slidableFactory;

    private StageFactory $stageFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        SlidableFactory $slidableFactory,
        StageFactory $stageFactory,
        SiteHeaderFactory $siteHeaderFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->slidableFactory = $slidableFactory;
        $this->stageFactory = $stageFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
    }

    public function forCareerPage(TraversableNodeInterface $careerPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($careerPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $careerPage, $stage, $subgraph);
    }

    public function forPrivacyPage(TraversableNodeInterface $privacyPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($privacyPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $privacyPage, $stage, $subgraph);
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        $videoStage = $this->stageFactory->forVideoStageIntroduction($homepage, $inBackend);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($homepage);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $homepage,
                $homepage,
                $subgraph
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
                        AnchorNavigationVariant::regular(),
                        array_map(
                            fn(Anchor $anchor): Link
                            => (new LinkFactory())->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                new ContentContainer(
                    ContentContainerVariant::regular(),
                    new AnimatedContentContainer(
                        AnimatedContentContainerVariant::fadeAndTranslate(),
                        new Headline(
                            HeadlineSize::mediumItalic(),
                            HeadlineColor::neutralContrast(),
                            HeadlineType::h1(),
                            Editable::fromNodeProperty($homepage, 'headline')
                        ),
                        AnimatedContentContainerFactory::getClassForVisibleContainer(
                            AnimatedContentContainerVariant::fadeAndTranslate()
                        ),
                        AnimatedContentContainerFactory::getClassForInvisibleContainer(
                            AnimatedContentContainerVariant::fadeAndTranslate()
                        ),
                    )
                ),
                new ContentContainer(
                    ContentContainerVariant::smallText(),
                    Collection::fromIterable(array_filter([
                        $homepage->getProperty('teaser_text_headline') || $inBackend
                            ? new Headline(
                                HeadlineSize::verySmall(),
                                HeadlineColor::neutralContrast(),
                                HeadlineType::div(),
                                Editable::fromNodeProperty($homepage, 'teaser_text_headline')
                            )
                            : null,
                        new Text(
                            TextVariant::neutralContrastFiraPro(),
                            Editable::fromNodeProperty($homepage, "text"),
                            false
                        )
                    ]))
                ),
                Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity)
        );
    }

    public function forSearchResultsPage(TraversableNodeInterface $searchResultsPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): PageInterface
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
        $breadcrumb = (new BreadcrumbFactory())->forPage($searchResultsPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $searchResultsPage,
                $siteNode,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::headerStage(),
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::regular(),
                    (new SearchFormFactory())->create($subgraph)
                ),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forWebPage(TraversableNodeInterface $site, TraversableNodeInterface $webPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($webPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $webPage, $stage, $subgraph);
    }

    public function forCareerRootPage(TraversableNodeInterface $site, TraversableNodeInterface $careerRootPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($careerRootPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $careerRootPage, $stage, $subgraph);
    }

    public function forPeopleAndCultureWebPage(TraversableNodeInterface $careerPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($careerPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $careerPage, $stage, $subgraph);
    }

    public function forContactPage(TraversableNodeInterface $site, TraversableNodeInterface $contactPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $languages = $subgraph->getDimensions()['language'];
        $language = reset($languages);
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($contactPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($contactPage, $subgraph);

        $anchors = (new Sailor())->findAvailableAnchorsForDocument($contactPage);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $contactPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
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
                    ContentContainerVariant::headerStage(),
                    $stage
                ),
                Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::blank(),
                    (new ContactFormFactory())->create(
                        $site,
                        $language,
                        $inBackend,
                        Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('contactonsuccess')), 'Hoerbiger.HoerbigerCom:ContentSlot'),
                        Content::fromNode($contactPage->findNamedChildNode(NodeName::fromString('contactonfail')), 'Hoerbiger.HoerbigerCom:ContentSlot')
                    )
                ),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forChroniclePage(TraversableNodeInterface $site, TraversableNodeInterface $chroniclePage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($chroniclePage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $chroniclePage, $stage, $subgraph);
    }

    public function forNotFoundPage(TraversableNodeInterface $site, TraversableNodeInterface $notFoundPage, ContentContext $subgraph, bool $inBackend): PageInterface
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
                $subgraph
            ),
            Collection::fromIterable(array_filter([
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
                    ContentContainerVariant::headerStage(),
                    $stage
                ),
                Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity)
        );
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $stage, ContentContext $subgraph): PageInterface
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
                            => (new LinkFactory())->forAnchor($anchor),
                            $anchors
                        )
                    )
                    : null,
                new ContentContainer(
                    ContentContainerVariant::headerStage(),
                    $stage
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                )
            ]), $identity)
        );
    }
}
