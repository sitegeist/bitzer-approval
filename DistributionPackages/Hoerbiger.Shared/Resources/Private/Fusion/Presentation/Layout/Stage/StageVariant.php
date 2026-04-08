<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum StageVariant:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    case VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';
    case VARIANT_FULLMEDIATEXTCENTER = 'fullMediaTextCenter';
    case VARIANT_STACK = 'stack';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_SERVICE = 'service';
    case VARIANT_DEFAULT = 'default';
    case VARIANT_PRESS = 'press';
    case VARIANT_PRESS_LARGE = 'pressLarge';
    case VARIANT_PRESS_OVERVIEW = 'pressOverview';

    public static function fromTheme(?Theme $theme): self
    {
        if (!$theme || $theme === THEME::VALUE_DEFAULT) {
            return self::VARIANT_DEFAULT;
        }

        return self::from($theme->value);
    }
}
