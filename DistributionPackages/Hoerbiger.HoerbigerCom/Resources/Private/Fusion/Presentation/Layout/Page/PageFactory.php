<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm\SearchFormFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm\ContactFormFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\ScrollToTop\ScrollToTop;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
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
        $stage = $this->stageFactory->forFullMediaIntroduction($careerPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $careerPage, $stage, $subgraph);
    }

    public function forPrivacyPage(TraversableNodeInterface $privacyPage, TraversableNodeInterface $siteNode, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($privacyPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($siteNode, $privacyPage, $stage, $subgraph);
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        $videoStage = $this->stageFactory->forVideoStageIntroduction($homepage, $inBackend);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $homepage,
                $homepage,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ScrollToTop(),
                // @TODO REMOVE old stage
                $videoStage
                    ? new ContentContainer(
                        ContentContainerVariant::blank(),
                        $videoStage
                    )
                    : null,
                Content::fromNode(
                    $homepage->findNamedChildNode(NodeName::fromString('stage')),
                    'Hoerbiger.HoerbigerCom:ContentSlot'
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
        $stage = $this->stageFactory->forFullMediaIntroduction($searchResultsPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($searchResultsPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $searchResultsPage,
                $siteNode,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
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
        $stage = $this->stageFactory->forFullMediaIntroduction($webPage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $webPage, $stage, $subgraph);
    }

    public function forCareerRootPage(TraversableNodeInterface $site, TraversableNodeInterface $careerRootPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($careerRootPage, $subgraph, $inBackend);

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
        $stage = $this->stageFactory->forFullMediaIntroduction($contactPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($contactPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $contactPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
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
        $stage = $this->stageFactory->forFullMediaIntroduction($chroniclePage, $subgraph, $inBackend);

        return $this->forDocumentWithIntroduction($site, $chroniclePage, $stage, $subgraph);
    }

    public function forNotFoundPage(TraversableNodeInterface $site, TraversableNodeInterface $notFoundPage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($notFoundPage, $subgraph, $inBackend);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $notFoundPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ScrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
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

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new scrollToTop(),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
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
