<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder;

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Application\CountryDirectory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Neos\Flow\I18n\Locale;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class EventFinderFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly CsvPOTranslator $csvPOTranslator,
        private readonly CountryDirectory $countryDirectory,
    ) {
    }

    public function create(string $locale, ContentContext $subgraph): EventFinder
    {
        $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
        $uriBuilder->reset()->setCreateAbsoluteUri(true);

        $labels = $this->csvPOTranslator->getLabels(EventFinder::class);
        foreach ($this->countryDirectory->findAll(new Locale($locale)) as $country) {
            $labels['label.filters.countryCode.' . $country->getCode()] = $country->getName();
        }

        return new EventFinder(
            locale: $locale,
            labels: $labels,
            endpointUri: new Uri($uriBuilder->uriFor(
                actionName: 'get',
                controllerName: 'Calendar',
                packageKey: 'Hoerbiger.HoerbigerCom.Events',
                subPackageKey: 'Application\CalendarQuery',
            ))
        );
    }
}
