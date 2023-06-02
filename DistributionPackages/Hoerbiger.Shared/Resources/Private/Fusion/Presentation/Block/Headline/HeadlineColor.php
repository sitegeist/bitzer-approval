<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HeadlineColor: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case COLOR_BRAND = 'brand';
    case COLOR_WHITE = 'white';
    case COLOR_TEXT = 'text';
    case COLOR_EXCITE = 'excite';
    case COLOR_EXCITE_CONTRAST = 'exciteContrast';
    case COLOR_EXPERIENCE = 'experience';
    case COLOR_EXPERIENCE_CONTRAST = 'experienceContrast';
    case COLOR_LITE = 'lite';
    case COLOR_LITE_CONTRAST = 'liteContrast';
    case COLOR_SERVICE_CONTRAST = 'serviceContrast';
    case COLOR_NEUTRAL_CONTRAST = 'neutralContrast';

    public static function tryFromTheme(?Theme $theme, bool $contrast): ?self
    {
        return match ($theme) {
            Theme::VALUE_SERVICE => self::COLOR_SERVICE_CONTRAST,
            Theme::VALUE_DEFAULT => self::COLOR_BRAND,
            null => null,
            default => self::tryFrom($theme->value . ($contrast ? 'Contrast' : '')),
        };
    }
}
