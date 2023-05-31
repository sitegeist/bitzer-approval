<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum SeparatorVariant: string
{
    case VARIANT_REGULAR = 'regular';
    case VARIANT_BOLD = 'bold';
    case VARIANT_BOLD_RED = 'boldRed';
    case VARIANT_FULL_WIDTH_WITH_CONTENT = 'fullWidthWithContent';

    public static function fromString(string $string): self
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            'bold' => self::VARIANT_BOLD,
            'boldRed' => self::VARIANT_BOLD_RED,
            'fullWidthWithContent' => self::VARIANT_FULL_WIDTH_WITH_CONTENT,
            default => null
        };
    }

}
