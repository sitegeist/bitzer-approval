<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class SynchronizedProperty
{
    public function __construct(
        public readonly string $name,
        public readonly ?PropertySynchronizationStrategy $strategy
    ) {
    }
}
