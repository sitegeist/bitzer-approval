<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum SiteHeaderVariant: string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_TRANSPARENT = 'transparent';
}
