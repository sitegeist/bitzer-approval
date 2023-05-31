<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum FigureObjectPosition: string
{
    case POSITION_TOPLEFT = 'topLeft';
    case POSITION_TOP = 'top';
    case POSITION_TOPRIGHT = 'topRight';
    case POSITION_LEFT = 'left';
    case POSITION_CENTER = 'center';
    case POSITION_RIGHT = 'right';
    case POSITION_BOTTOMLEFT = 'bottomLeft';
    case POSITION_BOTTOM = 'bottom';
    case POSITION_BOTTOMRIGHT = 'bottomRight';

    public static function fromString(string $string): self
    {
        return match ($string){
            'topLeft' => self::POSITION_TOPLEFT,
            'top' => self::POSITION_TOP,
            'topRight' => self::POSITION_TOPRIGHT,
            'left' => self::POSITION_LEFT,
            'center' => self::POSITION_CENTER,
            'right' => self::POSITION_RIGHT,
            'bottomLeft' => self::POSITION_BOTTOMLEFT,
            'bottom' => self::POSITION_BOTTOM,
            'bottomRight' => self::POSITION_BOTTOMRIGHT,
            default => null
        };
    }

}
