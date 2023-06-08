<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
enum PressOverviewVariant: string
{
    case VARIANT_REGULAR = 'regular';
    case VARIANT_TOP_NEWS = 'topNews';

    public static function fromString(string $string): self|null
    {
        return match($string){
            'regular' => self::VARIANT_REGULAR,
            'topNews' => self::VARIANT_TOP_NEWS,
            default => null
        };
    }

}
