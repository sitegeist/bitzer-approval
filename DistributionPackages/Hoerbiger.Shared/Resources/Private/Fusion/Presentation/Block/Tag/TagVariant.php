<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum TagVariant: string
{
    case VARIANT_BRANDCOLOR = 'brandColor';
    case VARIANT_BRANDCOLOR_CONTRAST = 'brandColorContrast';
    case VARIANT_TRANSPARENT = 'transparent';
    case VARIANT_HEIGHT_FULL = 'heightFull';

    public static function fromString(string $string): self
    {
        return match ($string){
            'brandColor' => self::VARIANT_BRANDCOLOR,
            'brandColorContrast' => self::VARIANT_BRANDCOLOR_CONTRAST,
            'transparent' => self::VARIANT_TRANSPARENT,
            'heightFull' => self::VARIANT_HEIGHT_FULL,
            default => null
        };
    }

}
