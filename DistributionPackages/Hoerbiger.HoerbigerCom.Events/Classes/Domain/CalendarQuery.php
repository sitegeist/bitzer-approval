<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Domain;

use Hoerbiger\HoerbigerCom\Domain\Stuff\ActiveFilters;
use Hoerbiger\HoerbigerCom\Domain\Stuff\SearchPage;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class CalendarQuery
{
    public function __construct(
        public string $locale,
        public ActiveFilters $activeFilters,
        public SearchPage $page,
        public bool $archived = false,
    ) {
    }
}
