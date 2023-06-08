<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
enum HeadlineVariant: string
{
    case VARIANT_LARGEBRAND = 'largeBrand';
    case VARIANT_LARGEWHITE = 'largeWhite';
    case VARIANT_LARGE = 'large';
    case VARIANT_MEDIUMBRAND = 'mediumBrand';
    case VARIANT_MEDIUMWHITE = 'mediumWhite';
    case VARIANT_MEDIUM = 'medium';


    public static function fromString(string $string): self|null
    {
        return match ($string){
            'largeBrand' => self::VARIANT_LARGEBRAND,
            'largeWhite' => self::VARIANT_LARGEWHITE,
            'large' => self::VARIANT_LARGE,
            'mediumBrand' => self::VARIANT_MEDIUMBRAND,
            'mediumWhite' => self::VARIANT_MEDIUMWHITE,
            'medium' => self::VARIANT_MEDIUM,
            default => null
        };
    }

}
