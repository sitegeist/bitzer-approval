<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum AnimatedContentContainerVariant:string
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_FADE_IN = 'fadeIn';
    case VARIANT_FADE_AND_TRANSLATE = 'fadeAndTranslate';
}
