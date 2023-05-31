<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum HeadlineType: string
{
    case TYPE_H1 = 'h1';
    case TYPE_H2 = 'h2';
    case TYPE_DIV = 'div';

    public static function fromString(string $string): self
    {
        return match ($string){
            'h1' => self::TYPE_H1,
            'h2' => self::TYPE_H2,
            'div' => self::TYPE_DIV,
            default => null
        };
    }

}
