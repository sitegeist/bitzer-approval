<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Domain;

use Hoerbiger\HoerbigerCom\Domain\Stuff\AvailableFilters;
use Hoerbiger\HoerbigerCom\Domain\Stuff\Things;
use Neos\Flow\Annotations as Flow;
use Sitegeist\SchemeOnYou\Domain\Metadata\Response;

#[Flow\Proxy(false)]
#[Response(statusCode: 200, description: 'Calendar events')]
final readonly class CalendarResponse
{
    public function __construct(
        public int $numberOfPages,
        public Things $results,
        public int $totalNumberOfResults,
        public AvailableFilters $availableFilters,
    ) {
    }
}
