<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum IconColor: string
{
    case COLOR_CURRENT = 'current';
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
    case COLOR_DEFAULT = 'default';


    public static function fromString(string $string): self|null
    {
        return match ($string){
            'current' => self::COLOR_CURRENT,
            'brand' => self::COLOR_BRAND,
            'white' => self::COLOR_WHITE,
            'text' => self::COLOR_TEXT,
            'excite' => self::COLOR_EXCITE,
            'exciteContrast' => self::COLOR_EXCITE_CONTRAST,
            'experience' => self::COLOR_EXPERIENCE,
            'experienceContrast' => self::COLOR_EXPERIENCE_CONTRAST,
            'lite' => self::COLOR_LITE,
            'liteContrast' => self::COLOR_LITE_CONTRAST,
            'serviceContrast' => self::COLOR_SERVICE_CONTRAST,
            'default' => self::COLOR_DEFAULT,
            default => null
        };
    }

    public static function tryFromTheme(?Theme $theme, bool $contrast): ?self
    {
        return $theme
            ? self::fromString(
                $theme->getIsService()
                    ? $theme->getValue() . 'Contrast'
                    : $theme->getValue() . ($contrast ? 'Contrast' : '')
            )
            : null;
    }

}
