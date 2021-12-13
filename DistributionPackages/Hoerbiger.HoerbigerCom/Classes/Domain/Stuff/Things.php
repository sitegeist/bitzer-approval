<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,Thing>
 */
final class Things implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var array<int,Thing>
     */
    private array $things;

    /**
     * @param array<int,Thing> $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $element) {
            if (!$element instanceof Thing) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . Thing::class);
            }
        }
        $this->things = $array;
    }

    /**
     * @return \ArrayIterator<int,Thing>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->things);
    }

    public function count(): int
    {
        return count($this->things);
    }

    /**
     * @return array<int,Thing>
     */
    public function jsonSerialize(): array
    {
        return $this->things;
    }
}
