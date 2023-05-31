<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum LinkVariant: string
{
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

    public static function fromString(string $string): self
    {
        return match ($string){
            'container' => self::VARIANT_CONTAINER,
            'label' => self::VARIANT_LABEL,
            'touchIcon' => self::VARIANT_TOUCHICON,
            'squareTouchIcon' => self::VARIANT_SQUARETOUCHICON,
            'noStyles' => self::VARIANT_NOSTYLES,
            'siteFooter' => self::VARIANT_SITEFOOTER,
            'plainLabel' => self::VARIANT_PLAINLABEL,
            'iconLabel' => self::VARIANT_ICONLABEL,
            'labelExcite' => self::VARIANT_LABELEXCITE,
            'labelExperience' => self::VARIANT_LABELEXPERIENCE,
            'labelLite' => self::VARIANT_LABELLITE,
            'labelService' => self::VARIANT_LABELSERVICE,
            'labelDefault' => self::VARIANT_LABELDEFAULT,
            default => null
        };
    }

    public static function tryFromTheme(?Theme $theme): ?self
    {
        return $theme
            ? self::fromString('label' . (ucfirst($theme->getValue())))
            : null;
    }

}
