<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum VideoVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_SERVICE = 'service';
    case VARIANT_SLIDER = 'slider';
    case VARIANT_SIMPLE_LOOP = 'simpleLoop';
    case VARIANT_FULL = 'full';

    public static function tryFromTheme(?Theme $theme): self
    {
        if (!$theme || $theme === THEME::VALUE_DEFAULT) {
            return self::VARIANT_REGULAR;
        }

        return self::tryFrom($theme->value);
    }
}
