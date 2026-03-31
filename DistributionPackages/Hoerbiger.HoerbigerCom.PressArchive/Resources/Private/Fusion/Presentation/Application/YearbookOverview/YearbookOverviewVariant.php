<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\YearbookOverview;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum YearbookOverviewVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_BIG_CARDS = 'bigCards';
    case VARIANT_SMALL_CARDS = 'smallCards';
}
