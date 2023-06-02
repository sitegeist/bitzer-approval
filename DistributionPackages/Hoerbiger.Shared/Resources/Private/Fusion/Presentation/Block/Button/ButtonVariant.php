<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum ButtonVariant: string
{
    use StringComponentVariant;

    case VARIANT_CTA = 'cta';
    case VARIANT_CTA_CONTRAST = 'ctaContrast';
    case VARIANT_CONTRAST = 'contrast';
    case VARIANT_PLAIN = 'plain';
}
