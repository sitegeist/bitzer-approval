<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

enum PropertySynchronizationStrategy: string
{
    case ONCE = 'once';
    case SYNC = 'sync';
}
