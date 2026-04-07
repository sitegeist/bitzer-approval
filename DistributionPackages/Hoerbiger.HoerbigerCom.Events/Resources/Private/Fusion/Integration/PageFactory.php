<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Integration;

use Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder\EventFinderFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly StageFactory $stageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
        private readonly EventFinderFactory $eventFinderFactory,
    ) {
    }

    public function forEventsPage(
        Node $site,
        Node $calendarPage,
        ContentContext $subgraph,
        bool $inBackend
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($calendarPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($calendarPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $calendarPage,
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
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULAR,
                    new Stack(
                        StackVariant::VARIANT_SM,
                        Collection::fromIterable(array_filter([
                            $inBackend || $calendarPage->getProperty('metaHeadline')
                                ? new Separator(
                                SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                                Editable::fromNodeProperty($calendarPage, 'metaHeadline')
                            ) : null,

                            $inBackend || $calendarPage->getProperty('eventsHeadline')
                                ? new Headline(
                                    HeadlineSize::SIZE_MEDIUM_ITALIC,
                                    HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($calendarPage, 'eventsHeadline')
                                ) : null,

                            $inBackend || $calendarPage->getProperty('eventsText')
                                ? new Text(
                                    TextVariant::VARIANT_LITE_CONTRAST,
                                    Editable::fromNodeProperty($calendarPage, 'eventsText'),
                                    false,
                                    false
                                ) : null,

                            new ContentContainer(
                                ContentContainerVariant::VARIANT_BLANK,
                                $this->eventFinderFactory->create(
                                    locale: $subgraph->getTargetDimensions()['language'],
                                    calendarId: $calendarPage->getIdentifier(),
                                )
                            )
                        ]))
                    )
                ),
                Content::fromNode($calendarPage->findNamedChildNode(NodeName::fromString('main'))),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forEventPage(
        Node $site,
        Node $eventPage,
        ContentContext $subgraph,
        bool $inBackend
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($eventPage, $subgraph, $inBackend);

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($eventPage, $subgraph);

        return new Page(
            $this->siteHeaderFactory->forDocument(
                $eventPage,
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
                Content::fromNode($eventPage->findNamedChildNode(NodeName::fromString('main'))),
            ]), $identity)
        );
    }

}
