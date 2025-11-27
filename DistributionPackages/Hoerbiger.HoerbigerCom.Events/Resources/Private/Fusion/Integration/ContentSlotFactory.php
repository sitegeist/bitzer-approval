<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Integration;

use Hoerbiger\HoerbigerCom\Events\Domain\Events;
use Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder\EventFinder;
use Hoerbiger\HoerbigerCom\Events\Presentation\Block\EventCard\EventCardFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly Events $events,
        private readonly CsvPOTranslator $csvPOTranslator,
        private readonly EventCardFactory $eventCardFactory,
    ) {
    }

    public function forContentNode(
        Node $contentNode,
        Node $documentNode,
        Node $site,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
        return match ((string)$contentNode->getNodeTypeName()) {
            'Hoerbiger.HoerbigerCom.Events:Content.UpcomingEvents'
                => $this->forUpcomingEventsNode($contentNode, $site, $subgraph, $inBackend),
            default => throw new \InvalidArgumentException(
                'Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName(),
                1684855586
            )
        };
    }

    private function forUpcomingEventsNode(
        Node $upcomingEventNode,
        Node $site,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
        $allNewsLabel = $this->csvPOTranslator->getLabel(EventFinder::class, 'label.readMore');

        $upcomingEvents = $this->events->findLatest(
            null,
            null,
            $upcomingEventNode->getProperty('numberOfEvents'),
            $subgraph
        )->toArray();

        return $upcomingEvents
            ?   new ContentContainer(
                ContentContainerVariant::VARIANT_REGULAR,
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_BLANK,
                        new Stack(
                            StackVariant::VARIANT_SM,
                            Collection::fromIterable(array_filter([
                                $inBackend || $upcomingEventNode->getProperty('metaHeadline')
                                    ? new Separator(
                                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                                        Editable::fromNodeProperty($upcomingEventNode, 'metaHeadline')
                                    ) : null,
                                new Headline(
                                    HeadlineSize::SIZE_MEDIUM_ITALIC,
                                    HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($upcomingEventNode, 'headline')
                                ),
                                new CacheSegment(
                                    new Grid(
                                        GridVariant::VARIANT_THREECOLUMNSGAP,
                                        Collection::fromIterable(
                                            $upcomingEvents,
                                            function (TraversableNodeInterface $event): SlotInterface {
                                                /** @var Node $event */
                                                $isExternalEvent = $event->getNodeType()->isOfType(
                                                    'Hoeriger.HoerbigerCom.Events:Document.ExternalEvent'
                                                );
                                                $targetProperty = $event->getProperty('target');
                                                return $this->eventCardFactory->forEvent(
                                                    $event,
                                                    $isExternalEvent,
                                                    (bool)$targetProperty || !$isExternalEvent
                                                );
                                            }
                                        )
                                    ),
                                    'Hoerbiger.HoerbigerCom.Events:CacheSegment.UpcomingEventsList'
                                )
                            ]))
                        )
                    )
                )
            )
            : ($inBackend
                ? new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULAR,
                    Value::fromString('Keine bevorstehenden Veranstaltungen gefunden')
                )
                : Value::fromString('')
            );
    }
}
