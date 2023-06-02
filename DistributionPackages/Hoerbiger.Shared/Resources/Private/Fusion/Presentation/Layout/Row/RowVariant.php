<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Row;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum RowVariant:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_CH = 'ch';
    case VARIANT_XS = 'xs';
    case VARIANT_SM = 'sm';
    case VARIANT_MD = 'md';
    case VARIANT_LG = 'lg';
    case VARIANT_GALLERY = 'gallery';
    case VARIANT_ALTERNATEMETALINE = 'alternateMetaline';
    case VARIANT_ALTERNATEMETALINE_ELLIPSIS = 'alternateMetalineEllipsis';
}
