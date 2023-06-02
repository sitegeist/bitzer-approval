<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum SliderVariant:string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_APPLICATION_WORLD = 'applicationWorld';
}
