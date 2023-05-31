<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum HeadlineSize: string
{
    case SIZE_SUPER_LARGE = 'superLarge';
    case SIZE_SUPER_LARGE_LIGHT = 'superLargeLight';
    case SIZE_EXTRA_LARGE = 'extraLarge';
    case SIZE_LARGE = 'large';
    case SIZE_LARGE_CENTERED = 'largeCentered';
    case SIZE_MEDIUM = 'medium';
    case SIZE_MEDIUM_ITALIC = 'mediumItalic';
    case SIZE_SMALL = 'small';
    case SIZE_VERY_SMALL = 'verySmall';
    case SIZE_VERY_SMALL_ITALIC = 'verySmallItalic';
    case SIZE_VERY_SMALL_REGULAR = 'verySmallRegular';
    case SIZE_TILE = 'tile';
    case SIZE_TINY = 'tiny';
    case SIZE_HEADER_STAGE = 'headerStage';


    public static function fromString(string $string): self
    {
        return match ($string){
            'superLarge' => self::SIZE_SUPER_LARGE,
            'superLargeLight' => self::SIZE_SUPER_LARGE_LIGHT,
            'extraLarge' => self::SIZE_EXTRA_LARGE,
            'large' => self::SIZE_LARGE,
            'largeCentered' => self::SIZE_LARGE_CENTERED,
            'medium' => self::SIZE_MEDIUM,
            'mediumItalic' => self::SIZE_MEDIUM_ITALIC,
            'small' => self::SIZE_SMALL,
            'verySmall' => self::SIZE_VERY_SMALL,
            'verySmallItalic' => self::SIZE_VERY_SMALL_ITALIC,
            'verySmallRegular' => self::SIZE_VERY_SMALL_REGULAR,
            'tile' => self::SIZE_TILE,
            'tiny' => self::SIZE_TINY,
            'headerStage' => self::SIZE_HEADER_STAGE,
            default => null
        };
    }

}
