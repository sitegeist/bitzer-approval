<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int|string,SearchType>
 */
final class SearchTypes implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var array<int|string,SearchType>
     */
    private array $searchTypes;

    public function __construct(SearchType ...$searchTypes)
    {
        $this->searchTypes = $searchTypes;
    }

    /**
     * @return \ArrayIterator<int|string,SearchType>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->searchTypes);
    }

    public function count(): int
    {
        return count($this->searchTypes);
    }

    public function isEmpty(): bool
    {
        return empty($this->searchTypes);
    }

    /**
     * @return array<int|string,SearchType>
     */
    public function jsonSerialize(): array
    {
        return $this->searchTypes;
    }
}
