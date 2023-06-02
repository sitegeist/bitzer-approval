<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum PartialFigureEclipseVariant: string
{
    use StringComponentVariant;

    case VARIANT_IMAGELEFT = 'imageLeft';
    case VARIANT_IMAGERIGHT = 'imageRight';
}
