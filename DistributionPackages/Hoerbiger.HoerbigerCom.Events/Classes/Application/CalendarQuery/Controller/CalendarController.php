<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Application\CalendarQuery\Controller;

use Hoerbiger\HoerbigerCom\Domain\Stuff\StuffResponse;
use Hoerbiger\HoerbigerCom\Events\Domain\Calendar;
use Hoerbiger\HoerbigerCom\Events\Domain\CalendarQuery;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use Sitegeist\SchemeOnYou\Application\OpenApiController;

class CalendarController extends OpenApiController
{
    public function __construct(
        private readonly Calendar $calendar,
        private readonly LocalizationService $localizationService
    ) {
    }

    public function getAction(CalendarQuery $query): StuffResponse
    {
        $this->localizationService->getConfiguration()->setCurrentLocale(new Locale($query->locale));

        return $this->calendar->findByQuery($query);
    }
}
