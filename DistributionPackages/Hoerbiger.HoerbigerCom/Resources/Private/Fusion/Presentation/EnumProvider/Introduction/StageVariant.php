<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\EnumProvider\Introduction;

/*
 * This file is part of the MeisterWerke.MeisterwerkeCom package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum StageVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    case VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';
}
