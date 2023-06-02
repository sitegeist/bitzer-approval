<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MetaHeadline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum MetaHeadlineVariant: string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
}
