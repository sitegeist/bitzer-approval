<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum IconSize: string
{
    case SIZE_SIZE_0_5 = 'size_0_5';
    case SIZE_SIZE_1 = 'size_1';
    case SIZE_SIZE_1_5 = 'size_1_5';
    case SIZE_SIZE_2 = 'size_2';
    case SIZE_SIZE_2_5 = 'size_2_5';
    case SIZE_SIZE_3 = 'size_3';
    case SIZE_SIZE_4 = 'size_4';
    case SIZE_PARENT = 'size_parent';

    public static function fromString(string $string): self
    {
        return match ($string){
            'size_0_5' => self::SIZE_SIZE_0_5,
            'size_1' => self::SIZE_SIZE_1,
            'size_1_5' => self::SIZE_SIZE_1_5,
            'size_2' => self::SIZE_SIZE_2,
            'size_2_5' => self::SIZE_SIZE_2_5,
            'size_3' => self::SIZE_SIZE_3,
            'size_4' => self::SIZE_SIZE_4,
            'size_parent' => self::SIZE_PARENT,
            default => null
        };

    }

}
