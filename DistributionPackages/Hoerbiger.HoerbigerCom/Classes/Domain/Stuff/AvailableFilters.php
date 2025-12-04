<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class AvailableFilters
{
    /**
     * @var array<int,AvailableFilter>
     */
    public array $items;

    public function __construct(AvailableFilter ...$items)
    {
        $this->items = array_values($items);
    }

    /**
     * @param array<string,mixed> $aggregation
     */
    public static function fromFacetAggregation(array $aggregation): self
    {
        $items = [];

        foreach ($aggregation as $facetName => $facet) {
            if (is_array($facet)) {
                $items[] = AvailableFilter::fromFacet($facetName, $facet[$facetName]);
            }
        }

        return new self(...$items);
    }
}
