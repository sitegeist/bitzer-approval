<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class AvailableFilter
{
    private function __construct(
        public string $name,
        public FilterOptions $options,
    ) {
    }

    /**
     * @param array<string,mixed> $facet
     */
    public static function fromFacet(string $name, array $facet): self
    {
        return new self(
            name: $name,
            options: FilterOptions::fromFacet(facet: $facet),
        );
    }
}
