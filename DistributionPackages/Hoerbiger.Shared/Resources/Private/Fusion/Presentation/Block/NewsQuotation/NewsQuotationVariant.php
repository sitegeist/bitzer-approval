<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\NewsQuotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum NewsQuotationVariant: string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
}
