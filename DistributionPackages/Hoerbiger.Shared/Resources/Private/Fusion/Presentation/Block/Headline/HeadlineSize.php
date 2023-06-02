<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HeadlineSize: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

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
}
