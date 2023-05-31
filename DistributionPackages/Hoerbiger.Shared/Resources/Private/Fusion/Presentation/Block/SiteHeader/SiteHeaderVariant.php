<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum SiteHeaderVariant: string
{
    case VARIANT_REGULAR = 'regular';
    case VARIANT_TRANSPARENT = 'transparent';

    public static function fromString(string $string): self
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            'transparent' => self::VARIANT_TRANSPARENT,
            default => null
        };
    }

}
