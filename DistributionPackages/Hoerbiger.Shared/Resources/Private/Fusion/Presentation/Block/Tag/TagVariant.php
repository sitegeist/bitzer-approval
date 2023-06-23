<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum TagVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_BRANDCOLOR = 'brandColor';
    case VARIANT_BRANDCOLOR_BOLD = 'brandColorBold';
    case VARIANT_BRANDCOLOR_LIGHT = 'brandColorLight';
    case VARIANT_BRANDCOLOR_CONTRAST = 'brandColorContrast';
    case VARIANT_NEUTRAL_CONTRAST = 'neutralContrast';
    case VARIANT_TRANSPARENT = 'transparent';
    case VARIANT_HEIGHT_FULL = 'heightFull';
}
