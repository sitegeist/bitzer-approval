<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum ButtonType: string
{
    case TYPE_REGULAR = 'regular';
    case TYPE_LINK = 'link';

    public static function fromString(string $string): self|null
    {
        return match ($string){
            'regular' => self::TYPE_REGULAR,
            'link' => self::TYPE_LINK,
            default => null
        };
    }
}
