<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class FilterOptions
{
    /**
     * @var array<int,FilterOption>
     */
    public array $items;

    public function __construct(FilterOption ...$items)
    {
        $this->items = array_values($items);
    }

    /**
     * @param array<string,mixed> $facet
     */
    public static function fromFacet(array $facet): self
    {
        $items = [];

        foreach ($facet['buckets'] as $bucket) {
            $items[] = FilterOption::fromBucket($bucket);
        }

        return new self(...$items);
    }
}
