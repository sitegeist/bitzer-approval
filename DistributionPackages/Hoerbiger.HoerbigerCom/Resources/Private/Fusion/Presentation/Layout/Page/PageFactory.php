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
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
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

    private SiteFooterFactory $siteFooterFactory;

    private SlidableFactory $slidableFactory;

    private StageFactory $stageFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        SiteFooterFactory $siteFooterFactory,
        SlidableFactory $slidableFactory,
        StageFactory $stageFactory,
        SiteHeaderFactory $siteHeaderFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->siteFooterFactory = $siteFooterFactory;
        $this->slidableFactory = $slidableFactory;
        $this->stageFactory = $stageFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
    }

    public function forHomePage(TraversableNodeInterface $homepage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $stage = $this->slidableFactory->forSliderPreviewMixins(
            $this->findChildNodesByNodeTypeFilterString($homepage, 'Hoerbiger.HoerbigerCom:Mixin.SliderPreview'),
            $inBackend
        );

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $homepage,
                $homepage,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::regular(),
                    new Stack(
                        StackVariant::xs(),
                        Collection::fromIterable(array_filter([
                            new Headline(
                                HeadlineVariant::brand(),
                                HeadlineType::h1(),
                                Editable::fromNodeProperty($homepage, 'headline')
                            ),
                            $inBackend || $homepage->getProperty('text')
                                ? new Text(
                                    TextVariant::lightContrast(),
                                    Editable::fromNodeProperty($homepage, 'text'),
                                    false
                                )
                                : null
                        ]), $identity)
                    )
                ),
                Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            $this->siteFooterFactory->forDocument($homepage, $homepage)
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
        $stage = $this->stageFactory->forFullMediaIntroduction($searchResultsPage, $inBackend);

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
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
                new ContentContainer(
                    ContentContainerVariant::stageAfterBreadcrumb(),
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::regular(),
                    (new SearchFormFactory())->create($subgraph)
                ),
            ]), $identity),
            $this->siteFooterFactory->forDocument($searchResultsPage, $siteNode)
        );
    }

    public function forWebPage(TraversableNodeInterface $site, TraversableNodeInterface $webPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($site, $webPage, $stage, $subgraph);
    }

    public function forContactPage(TraversableNodeInterface $site, TraversableNodeInterface $contactPage, bool $inBackend, ContentContext $subgraph): PageInterface
    {
        $languages = $subgraph->getDimensions()['language'];
        $language = reset($languages);
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($contactPage, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($contactPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $contactPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularX(),
                    $breadcrumb
                ),
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
                )
            ]), $identity),
            $this->siteFooterFactory->forDocument($contactPage, $site)
        );
    }

    public function forChroniclePage(TraversableNodeInterface $site, TraversableNodeInterface $chroniclePage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($chroniclePage, $inBackend);

        return $this->forDocumentWithIntroduction($site, $chroniclePage, $stage, $subgraph);
    }

    public function forNotFoundPage(TraversableNodeInterface $site, TraversableNodeInterface $notFoundPage, ContentContext $subgraph, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($notFoundPage, $inBackend);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $notFoundPage,
                $site,
                $subgraph
            ),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $stage
                ),
                Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
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
            $this->siteHeaderFactory->forDocument(
                $document,
                $site,
                $subgraph
            ),
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
