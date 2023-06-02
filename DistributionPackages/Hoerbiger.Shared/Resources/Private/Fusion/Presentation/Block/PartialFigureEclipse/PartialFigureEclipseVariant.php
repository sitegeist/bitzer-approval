<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum PartialFigureEclipseVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_IMAGELEFT = 'imageLeft';
    case VARIANT_IMAGERIGHT = 'imageRight';
}
