<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder;

use Hoerbiger\HoerbigerCom\Events\Application\EventsEndpoint;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class EventFinderFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly CsvPOTranslator $csvPOTranslator,
    ) {
    }

    public function create(string $locale, ContentContext $subgraph): EventFinder
    {
        return new EventFinder(
            $locale,
            array_merge(
                $this->csvPOTranslator->getLabels(EventFinder::class)
            ),
            EventsEndpoint::getUri(),
        );
    }
}
