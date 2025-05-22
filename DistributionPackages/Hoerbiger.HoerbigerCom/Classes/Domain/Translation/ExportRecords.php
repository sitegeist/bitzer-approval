<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<int,ExportRecord>
 */
#[Flow\Proxy(false)]
final class ExportRecords implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,ExportRecord>
     */
    private array $items;

    public function __construct(ExportRecord ...$items)
    {
        $this->items = array_values($items);
    }

    /**
     * @return \ArrayIterator<int,ExportRecord>|ExportRecord[]
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
