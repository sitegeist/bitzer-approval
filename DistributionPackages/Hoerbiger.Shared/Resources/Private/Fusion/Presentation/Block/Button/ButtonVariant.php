<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum ButtonVariant: string
{
    case VARIANT_CTA = 'cta';
    case VARIANT_CTA_CONTRAST = 'ctaContrast';
    case VARIANT_CONTRAST = 'contrast';
    case VARIANT_PLAIN = 'plain';

    public static function fromString(string $string): self
    {
        return match ($string){
            'cta' => self::VARIANT_CTA,
            'ctaContrast' => self::VARIANT_CTA_CONTRAST,
            'contrast' => self::VARIANT_CONTRAST,
            'plain' => self::VARIANT_PLAIN,
            default => null
        };
    }

}
