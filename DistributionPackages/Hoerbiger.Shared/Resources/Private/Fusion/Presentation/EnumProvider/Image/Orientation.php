<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Image;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum Orientation: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_HORIZONTAL = 'Horizontal';
    case VALUE_VERTICAL = 'Vertical';
    case VALUE_SQUARE = 'Square';
}

