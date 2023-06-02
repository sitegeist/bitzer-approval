<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum LinkVariant: string
{
    use StringComponentVariant;

    case VARIANT_CONTAINER = 'container';
    case VARIANT_LABEL = 'label';
    case VARIANT_TOUCHICON = 'touchIcon';
    case VARIANT_SQUARETOUCHICON = 'squareTouchIcon';
    case VARIANT_NOSTYLES = 'noStyles';
    case VARIANT_SITEFOOTER = 'siteFooter';
    case VARIANT_PLAINLABEL = 'plainLabel';
    case VARIANT_ICONLABEL = 'iconLabel';
    case VARIANT_LABELEXCITE = 'labelExcite';
    case VARIANT_LABELEXPERIENCE = 'labelExperience';
    case VARIANT_LABELLITE = 'labelLite';
    case VARIANT_LABELSERVICE = 'labelService';
    case VARIANT_LABELDEFAULT = 'labelDefault';

    public static function tryFromTheme(?Theme $theme): ?self
    {
        return $theme
            ? self::tryFrom('label' . (ucfirst($theme->value)))
            : null;
    }
}
