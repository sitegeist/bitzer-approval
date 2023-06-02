<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;

enum VideoVariant: string
{
    case VARIANT_REGULAR = 'regular';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_SERVICE = 'service';
    case VARIANT_SLIDER = 'slider';
    case VARIANT_SIMPLE_LOOP = 'simpleLoop';

    public static function fromString(string $string): self
    {
        return self::tryFrom($string);
    }

    public static function tryFromTheme(?Theme $theme): self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::VARIANT_REGULAR;
        }
        return self::fromString($theme->getValue());
    }

}
