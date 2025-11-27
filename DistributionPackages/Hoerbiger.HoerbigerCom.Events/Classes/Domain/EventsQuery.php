<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Domain;

use Hoerbiger\HoerbigerCom\Domain\Stuff\SearchPage;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class EventsQuery
{
    /**
     * @param array<string,string> $activeFilters
     */
    public function __construct(
        public readonly string $locale,
        public readonly array $activeFilters,
        public readonly SearchPage $page,
        public readonly bool $archived
    ) {
    }

    public static function fromJsonString(string $jsonString): self
    {
        return self::fromArray(\json_decode($jsonString, true, JSON_THROW_ON_ERROR));
    }

    /**
     * @param array<string,mixed> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['locale'],
            $array['activeFilters'],
            SearchPage::fromInt($array['page']),
            $array['archived']
        );
    }
}
