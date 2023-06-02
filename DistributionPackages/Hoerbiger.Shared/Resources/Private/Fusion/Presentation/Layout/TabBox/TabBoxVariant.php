<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum TabBoxVariant:string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_HORIZONTAL = 'horizontal';
}
