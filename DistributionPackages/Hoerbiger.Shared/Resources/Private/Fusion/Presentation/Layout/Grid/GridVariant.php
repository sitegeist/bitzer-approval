<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Grid;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum GridVariant:string
{
    use StringComponentVariant;

    case VARIANT_ONECOLUMN = 'oneColumn';
    case VARIANT_TWOCOLUMNS = 'twoColumns';
    case VARIANT_TWOCOLUMNSNOGAP = 'twoColumnsNoGap';
    case VARIANT_TWOCOLUMNSGAP = 'twoColumnsGap';
    case VARIANT_THREECOLUMNSGAP = 'threeColumnsGap';
    case VARIANT_THREECOLUMNSNARROWGAP = 'threeColumnsnNarrowGap';
    case VARIANT_TWOCOLUMNSFRMAXGAP = 'twoColumnsFrMaxGap';
    case VARIANT_TWOCOLUMNSTHIRDAUTOGAP = 'twoColumnsThirdAutoGap';
    case VARIANT_TWOCOLUMNSPRESS = 'twoColumnsPress';
    case VARIANT_ICONLEFT = 'iconLeft';
    case VARIANT_ICONRIGHT = 'iconRight';
}
