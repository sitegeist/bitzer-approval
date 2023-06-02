<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum FigureObjectPosition: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case POSITION_TOPLEFT = 'topLeft';
    case POSITION_TOP = 'top';
    case POSITION_TOPRIGHT = 'topRight';
    case POSITION_LEFT = 'left';
    case POSITION_CENTER = 'center';
    case POSITION_RIGHT = 'right';
    case POSITION_BOTTOMLEFT = 'bottomLeft';
    case POSITION_BOTTOM = 'bottom';
    case POSITION_BOTTOMRIGHT = 'bottomRight';
}
