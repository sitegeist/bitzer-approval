<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum SlidableVariant:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_APPLICATION_WORLD = 'applicationWorld';
}
