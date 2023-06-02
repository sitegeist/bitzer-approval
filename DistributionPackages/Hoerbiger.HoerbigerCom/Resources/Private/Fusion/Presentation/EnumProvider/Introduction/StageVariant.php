<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\EnumProvider\Introduction;

/*
 * This file is part of the MeisterWerke.MeisterwerkeCom package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum StageVariant: string
{
    use StringComponentVariant;

    case VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    case VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';
}
