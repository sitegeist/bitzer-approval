<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum BoxVariant:string
{
    use StringComponentVariant;

    case VARIANT_TEXTCENTER = 'textCenter';
    case VARIANT_NEUTRAL = 'neutral';
    case VARIANT_DECORATIVE = 'decorative';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_LIGHT = 'light';
    case VARIANT_SERVICE = 'service';
    case VARIANT_DEFAULT = 'default';

    public static function tryFromTheme(?Theme $theme): ?self
    {
        if (!$theme || $theme === Theme::VALUE_DEFAULT) {
            return self::VARIANT_DECORATIVE;
        }

        return self::from($theme->value);
    }
}
