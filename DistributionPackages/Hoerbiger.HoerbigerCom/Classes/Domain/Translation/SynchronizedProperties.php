<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<int,SynchronizedProperty>
 */
#[Flow\Proxy(false)]
final class SynchronizedProperties implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,SynchronizedProperty>
     */
    private array $items;

    public function __construct(SynchronizedProperty ...$items)
    {
        $this->items = array_values($items);
    }

    public function findByName(string $propertyName): ?SynchronizedProperty
    {
        foreach ($this->items as $synchronizedProperty) {
            if ($synchronizedProperty->name == $propertyName) {
                return $synchronizedProperty;
            }
        }
        return null;
    }

    /**
     * @return \ArrayIterator<int,SynchronizedProperty>|SynchronizedProperty[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }
    public function count(): int
    {
        return count($this->items);
    }
}
