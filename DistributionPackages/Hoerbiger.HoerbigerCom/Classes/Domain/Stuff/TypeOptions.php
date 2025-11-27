<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class TypeOptions implements \JsonSerializable
{
    /**
     * @var array<int,TypeOption>
     */
    public array $items;

    public function __construct(TypeOption ...$items)
    {
        $this->items = array_values($items);
    }

    /**
     * @param array<string,mixed> $aggregations
     */
    public static function fromAggregations(array $aggregations): self
    {
        return new self(...array_map(function (array $bucket): TypeOption {
            return new TypeOption(
                $bucket['key'],
                $bucket['doc_count']
            );
        }, $aggregations['facets']['searchType']['searchType']['buckets'] ?? []));
    }

    /**
     * @return array<int,TypeOption>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
