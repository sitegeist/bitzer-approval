<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm\SearchForm;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\IntroSection\FullMediaIntroSection\FullMediaIntroSection;
use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionFactory;
use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionVariant;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooter;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use http\Exception\InvalidArgumentException;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forHomePage(TraversableNodeInterface $homepage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($homepage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
    }

    public function forSearchResultsPage(TraversableNodeInterface $searchResultsPage, bool $inBackend): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $media = (new FigureFactory())->forImageMixin($searchResultsPage, $inBackend, true);
        if (!$media) {
            throw new \InvalidArgumentException('Image must be set for search results page in frontend mode.');
        }
        $media = $media->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center());

        $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
        $uriBuilder->setFormat('json');
        $endpointUri = new Uri(
            $uriBuilder->uriFor(
                'query',
                [],
                'Stuff',
                'Hoerbiger.HoerbigerCom',
                'Application'
            )
        );

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    new FullMediaIntroSection(
                        IntroSectionVariant::fullMediaTextLeft(),
                        new Headline(HeadlineVariant::regular(), HeadlineType::h1(), Value::fromString('[Ihre Suche]')),
                        null,
                        new Text(TextVariant::neutralContrast(), Value::fromString(''), false),
                        $media
                    )
                ),
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    new SearchForm($endpointUri)
                ),
            ]), $identity),
            new SiteFooter()
        );
    }

    public function forWebPage(TraversableNodeInterface $webPage, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $introSection */
        $introSection = (new IntroSectionFactory())->forFullMediaIntroduction($webPage, $inBackend);

        return $this->forDocumentWithIntroduction($webPage, $introSection);
    }

    public function forNotFoundPage(TraversableNodeInterface $notFoundPage): PageInterface
    {
        return new Page(
            new SiteHeader(),
            Content::fromNode($notFoundPage->findNamedChildNode(NodeName::fromString('main'))),
            new SiteFooter()
        );
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
