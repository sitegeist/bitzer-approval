<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class StuffResponse implements \JsonSerializable
{
    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
        ];
    }
}
