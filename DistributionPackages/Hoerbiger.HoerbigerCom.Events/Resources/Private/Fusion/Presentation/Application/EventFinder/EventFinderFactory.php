<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder;

use GuzzleHttp\Psr7\Uri;
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
        $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
        $uriBuilder->reset()->setCreateAbsoluteUri(true);

        return new EventFinder(
            locale: $locale,
            labels: array_merge(
                $this->csvPOTranslator->getLabels(EventFinder::class)
            ),
            endpointUri: new Uri($uriBuilder->uriFor(
                actionName: 'get',
                controllerName: 'Calendar',
                packageKey: 'Hoerbiger.HoerbigerCom.Events',
                subPackageKey: 'Application\CalendarQuery',
            ))
        );
    }
}
