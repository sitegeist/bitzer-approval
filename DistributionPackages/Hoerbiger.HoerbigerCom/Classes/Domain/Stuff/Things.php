<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Things implements \JsonSerializable
{
    /**
     * @var array<int,Thing>
     */
    public array $items;

    public function __construct(Thing ...$items)
    {
        $this->items = array_values($items);
    }

    /**
     * @return array<int,Thing>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
