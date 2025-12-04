<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class ActiveFilter
{
    public function __construct(
        public string $name,
        public string $value
    ) {
    }
}
