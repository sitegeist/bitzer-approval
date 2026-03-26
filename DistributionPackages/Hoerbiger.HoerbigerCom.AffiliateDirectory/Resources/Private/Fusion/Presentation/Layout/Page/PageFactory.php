<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\AffiliateFinderFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{

    public function __construct(
        private readonly StageFactory $stageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
    ) {
    }

    public function forAffiliateFinder(TraversableNodeInterface $site, TraversableNodeInterface $affiliateFinder, bool $inBackend, ContentContext $subGraph): Page
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($affiliateFinder, $subGraph);

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($affiliateFinder, $subGraph, $inBackend);

        /** @var SlotInterface $application */
        $application = (new AffiliateFinderFactory())->create($subGraph->getDimensions()['language'][0]);

        return new Page(
            $this->siteHeaderFactory->forDocument($affiliateFinder, $site, $subGraph, $inBackend, null),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARNEUTRAL,
                    new Stack(
                        StackVariant::VARIANT_XS_STRETCH,
                        Collection::fromIterable(array_filter([
                            $inBackend || $affiliateFinder->getProperty('applicationHeadline')
                                ? new Headline(
                                    HeadlineSize::SIZE_LARGE,
                                    HeadlineColor::COLOR_BRAND,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($affiliateFinder, 'applicationHeadline')
                                )
                                : null,
                            $inBackend || $affiliateFinder->getProperty('text')
                                ? new Text(
                                    TextVariant::VARIANT_NEUTRAL_CONTRAST,
                                    Editable::fromNodeProperty($affiliateFinder, 'text'),
                                    false
                                )
                                : null,
                            $application
                        ]), $identity)
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
}
