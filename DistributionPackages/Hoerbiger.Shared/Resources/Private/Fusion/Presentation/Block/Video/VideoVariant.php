<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum VideoVariant: string
{
    case VARIANT_REGULAR = 'regular';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_SERVICE = 'service';
    case VARIANT_SLIDER = 'slider';

    public static function fromString(string $string): self
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            'excite' => self::VARIANT_EXCITE,
            'experience' => self::VARIANT_EXPERIENCE,
            'lite' => self::VARIANT_LITE,
            'service' => self::VARIANT_SERVICE,
            'slider' => self::VARIANT_SLIDER,
            default => null
        };
    }

    public static function tryFromTheme(?Theme $theme): self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::VARIANT_REGULAR;
        }
        return self::fromString($theme->getValue());
    }

}
