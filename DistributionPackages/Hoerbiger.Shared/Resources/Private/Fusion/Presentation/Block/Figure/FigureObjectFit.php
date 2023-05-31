<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum FigureObjectFit: string
{
    case FIT_COVER = 'cover';
    case FIT_CONTAIN = 'contain';

    public static function fromString(string $string): self|null
    {
        return match ($string){
            'cover' => self::FIT_COVER,
            'contain' => self::FIT_CONTAIN,
            default => null
        };
    }

}
