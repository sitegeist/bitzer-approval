<?php

/*
 * This file is part of the BauwerkGroup.BauwerkParkettCom.Catalog package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Application;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<int,string>
 * @Flow\Proxy(false)
 */
final class BrowserLanguages implements \IteratorAggregate
{
    /**
     * @var array<int,string>
     */
    private array $values;

    /**
     * @param array<int,string> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function fromHttpHeader(string $header): self
    {
        $segments = explode(',', $header);

        return new self(array_map(
            fn ($value): string => \mb_substr($value, 0, \mb_strpos($value, ';') ?: null),
            $segments
        ));
    }


    /**
     * @return \ArrayIterator<int,string>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }
}
