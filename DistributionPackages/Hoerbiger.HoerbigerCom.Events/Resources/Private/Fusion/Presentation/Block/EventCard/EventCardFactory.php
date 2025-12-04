<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Block\EventCard;

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder\EventFinder;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\Model\Node;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class EventCardFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly CsvPOTranslator $csvPOTranslator,
        private readonly FormattedDateFactory $formattedDateFactory,
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly LinkFactory $linkFactory,
        private readonly FigureFactory $figureFactory,
    ) {
    }

    public function forEvent(
        Node $event,
        bool $isExternalEvent,
        bool $isLinked,
    ): EventCard {
        $inBackend = false;
        $link = $this->linkFactory->forLinkToDocument($event, null);

        $imageSource = $this->imageSourceFactory->forImageProperty($event, $inBackend, 'preview__image');
        $figure = $imageSource
            ? $link->asContainer()->withContent($this->figureFactory->forNavigationCard($imageSource))
            : null;


        $date = Value::fromString(
            $this->formattedDateFactory->createFullDate(
                $event->getProperty('startDate')
            ) . ($event->getProperty('endDate')
                ? ' - ' . $this->formattedDateFactory->createFullDate(
                    $event->getProperty('endDate')
                )
                : ''
            )
        );

        $targetProperty = $event->getProperty('target');

        $linkUri = $isExternalEvent
            ? new Uri($targetProperty ?: '/')
            : $this->uriService->getNodeUri($event);

        return new EventCard(
            $figure,
            $event->getProperty('type')
                ? Value::fromString(
                    $this->csvPOTranslator->getLabel(
                        EventFinder::class,
                        'label.filters.type.' . $event->getProperty('type')
                    )
                )
                : null,
            Value::fromString($event->getLabel()),
            new Grid(
                GridVariant::VARIANT_ICONLEFT,
                Collection::fromIterable([
                    new Icon("shared", "calendarWeek", IconSize::SIZE_SIZE_1),
                    $date
                ])
            ),
            $event->getProperty('location')
                ? new Grid(
                    GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable([
                        new Icon("shared", "geo", IconSize::SIZE_SIZE_1),
                        Value::fromString($event->getProperty('location'))
                    ])
                )
                : null,
            $event->getProperty('detailedLocation')
                ? new Grid(
                GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable([
                        new Icon("shared", "house", IconSize::SIZE_SIZE_1),
                        Value::fromString($event->getProperty('detailedLocation'))
                    ])
                )
                : null,
            new Button(
                ButtonType::TYPE_LINK,
                ButtonVariant::VARIANT_PLAIN,
                new Grid(
                    GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable([
                        new Icon("shared", "arrowRight", IconSize::SIZE_SIZE_1),
                        Value::fromString(
                            $this->csvPOTranslator->getLabelFromTranslationResource('Hoerbiger.HoerbigerCom', 'NavigationCard', 'link.label')
                        )
                    ])
                ),
                $linkUri,
                $isExternalEvent ? '_blank' : '_self',
                null
            )
        );
    }
}
