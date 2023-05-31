<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum PartialFigureEclipseVariant: string
{
    case VARIANT_IMAGELEFT = 'imageLeft';
    case VARIANT_IMAGERIGHT = 'imageRight';


    public static function fromString(string $string): self
    {
        return match ($string){
            'imageLeft' => self::VARIANT_IMAGELEFT,
            'imageRight' => self::VARIANT_IMAGERIGHT,
            default => null
        };
    }

}
