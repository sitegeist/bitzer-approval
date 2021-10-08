<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,SearchType>
 */
final class SearchTypes implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,SearchType>
     */
    private array $searchTypes;

    /**
     * @param array<int,mixed> $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $element) {
            if (!$element instanceof SearchType) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . SearchType::class);
            }
        }
        $this->searchTypes = $array;
    }

    /**
     * @return \ArrayIterator<int,SearchType>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->searchTypes);
    }

    public function count(): int
    {
        return count($this->searchTypes);
    }
}
