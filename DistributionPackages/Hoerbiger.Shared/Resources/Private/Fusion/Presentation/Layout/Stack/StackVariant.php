<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stack;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum StackVariant:string
{
    use StringComponentVariant;

    case VARIANT_NOGAP = 'noGap';
    case VARIANT_INLINE = 'inline';
    case VARIANT_XS = 'xs';
    case VARIANT_XS_STRETCH = 'xs_stretch';
    case VARIANT_SM = 'sm';
    case VARIANT_SM_STRETCH = 'sm_stretch';
    case VARIANT_MD = 'md';
    case VARIANT_MD_STRETCH = 'md_stretch';
    case VARIANT_LG = 'lg';
    case VARIANT_LG_STRETCH = 'lg_stretch';
    case VARIANT_XL = 'xl';
    case VARIANT_XL_STRETCH = 'xl_stretch';
    case VARIANT_META_PRESS = 'metaPress';
}
