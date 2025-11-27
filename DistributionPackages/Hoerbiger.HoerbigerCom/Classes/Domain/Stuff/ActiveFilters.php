<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class ActiveFilters
{
    /**
     * @var array<int,ActiveFilter>
     */
    public array $items;

    public function __construct(ActiveFilter ...$items)
    {
        $this->items = array_values($items);
    }
}
