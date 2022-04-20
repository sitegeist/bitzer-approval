<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,TypeOption>
 */
final class TypeOptions implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var array<int,TypeOption>
     */
    private array $options;

    /**
     * @param array<int,TypeOption> $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $element) {
            if (!$element instanceof TypeOption) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . TypeOption::class);
            }
        }
        $this->options = $array;
    }

    /**
     * @param array<string,mixed> $aggregations
     */
    public static function fromAggregations(array $aggregations): self
    {
        return new self(array_filter(array_map(function (array $bucket): ?TypeOption {
            return $bucket['key'] === SearchType::TYPE_WEBPAGE ? null : new TypeOption(
                $bucket['key'],
                $bucket['doc_count']
            );
        }, $aggregations['facets']['searchType']['searchType']['buckets'] ?? [])));
    }

    /**
     * @return \ArrayIterator<int,TypeOption>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->options);
    }

    public function count(): int
    {
        return count($this->options);
    }

    /**
     * @return array<int,TypeOption>
     */
    public function jsonSerialize(): array
    {
        return $this->options;
    }
}
