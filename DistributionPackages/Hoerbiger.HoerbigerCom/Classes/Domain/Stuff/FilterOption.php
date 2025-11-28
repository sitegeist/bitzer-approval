<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class FilterOption
{
    public function __construct(
        public string $value,
        public int $numberOfResults,
    ) {
    }

    /**
     * @param array<string,mixed> $bucket
     */
    public static function fromBucket(array $bucket): self
    {
        return new self(
            value: $bucket['key'],
            numberOfResults: $bucket['doc_count'],
        );
    }
}
