<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum TextVariant: string
{
    case VARIANT_DEFAULT = 'default';
    case VARIANT_CENTERED = 'centered';
    case VARIANT_LIGHT_CONTRAST = 'lightContrast';
    case VARIANT_BRAND_CONTRAST = 'brandContrast';
    case VARIANT_BRAND_CONTRAST_BOLD = 'brandContrastBold';
    case VARIANT_NEUTRAL_CONTRAST = 'neutralContrast';
    case VARIANT_NEUTRAL_CONTRAST_FIRA_PRO = 'neutralContrastFiraPro';
    case VARIANT_TRANSPARENT_CONTRAST = 'transparentContrast';
    case VARIANT_EXCITE = 'excite';
    case VARIANT_EXCITE_CONTRAST = 'exciteContrast';
    case VARIANT_EXPERIENCE = 'experience';
    case VARIANT_EXPERIENCE_CONTRAST = 'experienceContrast';
    case VARIANT_LITE = 'lite';
    case VARIANT_LITE_CONTRAST = 'liteContrast';
    case VARIANT_SERVICE_CONTRAST = 'serviceContrast';
    case VARIANT_SERVICE_RED = 'serviceRed';
    case VARIANT_SM = 'sm';

    public static function fromString(string $string): self|null
    {
    }

    public static function tryFromTheme(?Theme $theme, bool $contrast): ?self
    {

        if ($theme) {
            switch ($theme->getValue()) {
                case Theme::VALUE_SERVICE:
                    return self::fromString($theme->getValue() . 'Contrast');
                case Theme::VALUE_DEFAULT:
                    return self::VARIANT_DEFAULT;
                default:
                    return self::fromString($theme->getValue() . ($contrast ? 'Contrast' : ''));
            }
        }
        return null;
    }

}
