<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum TagVariant: string
{
    use StringComponentVariant;

    case VARIANT_BRANDCOLOR = 'brandColor';
    case VARIANT_BRANDCOLOR_CONTRAST = 'brandColorContrast';
    case VARIANT_TRANSPARENT = 'transparent';
    case VARIANT_HEIGHT_FULL = 'heightFull';
}
