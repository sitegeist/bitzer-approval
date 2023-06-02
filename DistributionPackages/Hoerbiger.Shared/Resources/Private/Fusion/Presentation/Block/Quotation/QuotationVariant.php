<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Quotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum QuotationVariant: string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_INLINE = 'inline';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_LITE = 'lite';
    case VARIANT_SERVICE = 'service';

    public static function forTheme(?Theme $theme): self
    {
        if (!$theme || $theme === Theme::VALUE_DEFAULT) {
            return self::VARIANT_REGULAR;
        }

        return self::from($theme->value);
    }
}
