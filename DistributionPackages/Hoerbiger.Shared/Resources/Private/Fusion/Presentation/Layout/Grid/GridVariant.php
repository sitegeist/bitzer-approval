<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Grid;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum GridVariant:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_ONECOLUMN = 'oneColumn';
    case VARIANT_ONECOLUMN_NOGAP_BORDER = 'oneColumnNoGapBorder';
    case VARIANT_TWOCOLUMNS = 'twoColumns';
    case VARIANT_TWOCOLUMNSNOGAP = 'twoColumnsNoGap';
    case VARIANT_TWOCOLUMNSGAP = 'twoColumnsGap';
    case VARIANT_THREECOLUMNSGAP = 'threeColumnsGap';
    case VARIANT_THREECOLUMNSNARROWGAP = 'threeColumnsNarrowGap';
    case VARIANT_TWOCOLUMNSFRMAXGAP = 'twoColumnsFrMaxGap';
    case VARIANT_TWOCOLUMNSTHIRDAUTOGAP = 'twoColumnsThirdAutoGap';
    case VARIANT_TWOCOLUMNSTHIRDBIGGAP = 'twoColumnsThirdBigGap';
    case VARIANT_TWOCOLUMNSPRESS = 'twoColumnsPress';
    case VARIANT_ICONLEFT = 'iconLeft';
    case VARIANT_ICONRIGHT = 'iconRight';

    case VARIANT_TWO_33_66 = 'twoColumns3366';
    case VARIANT_TWO_66_33 = 'twoColumns6633';
}
