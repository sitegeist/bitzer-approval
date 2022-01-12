<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\AffiliateFinderFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    private SiteFooterFactory $siteFooterFactory;

    private SiteHeaderFactory $siteHeaderFactory;

    public function __construct(StageFactory $stageFactory, SiteFooterFactory $siteFooterFactory, SiteHeaderFactory $siteHeaderFactory)
    {
        $this->stageFactory = $stageFactory;
        $this->siteFooterFactory = $siteFooterFactory;
        $this->siteHeaderFactory = $siteHeaderFactory;
    }

    public function forAffiliateFinder(TraversableNodeInterface $site, TraversableNodeInterface $affiliateFinder, bool $inBackend, ContentContext $subGraph): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = (new BreadcrumbFactory())->forPage($affiliateFinder, $subGraph);

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($affiliateFinder, $inBackend);

        /** @var SlotInterface $application */
        $application = (new AffiliateFinderFactory())->create($subGraph->getDimensions()['language'][0]);

        return new Page(
            $this->siteHeaderFactory->forDocument($affiliateFinder, $site, $subGraph),
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
                    ContentContainerVariant::regularNeutral(),
                    new Stack(
                        StackVariant::xsStretch(),
                        Collection::fromIterable(array_filter([
                            $inBackend || $affiliateFinder->getProperty('headline')
                                ? new Headline(
                                    HeadlineVariant::regular(),
                                    HeadlineType::h2(),
                                    Editable::fromNodeProperty($affiliateFinder, 'headline')
                                )
                                : null,
                            $inBackend || $affiliateFinder->getProperty('text')
                                ? new Text(
                                    TextVariant::neutralContrast(),
                                    Editable::fromNodeProperty($affiliateFinder, 'text'),
                                    false
                                )
                                : null,
                            $application
                        ]), $identity)
                    )
                )
            ]), $identity),
            $this->siteFooterFactory->forDocument($affiliateFinder, $site)
        );
    }
}
