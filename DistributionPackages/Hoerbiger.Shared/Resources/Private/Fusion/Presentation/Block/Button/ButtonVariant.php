<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum ButtonVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_CTA = 'cta';
    case VARIANT_CTA_CONTRAST = 'ctaContrast';
    case VARIANT_CONTRAST = 'contrast';
    case VARIANT_PLAIN = 'plain';
}
