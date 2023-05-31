<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum PressReleaseCardVariant: string
{
    case VARIANT_META = 'meta';
    case VARIANT_NEWS = 'news';
    case VARIANT_TOP = 'top';
    case VARIANT_TOP_NEWS = 'topNews';
    case VARIANT_HANNS = 'hanns';
    case VARIANT_YEARBOOK = 'yearbook';

    public static function fromString(string $string): self
    {
        return match ($string){
            'meta' => self::VARIANT_META,
            'news' => self::VARIANT_NEWS,
            'top' => self::VARIANT_TOP,
            'topNews' => self::VARIANT_TOP_NEWS,
            'hanns' => self::VARIANT_HANNS,
            'yearbook' => self::VARIANT_YEARBOOK,
            default => null
        };
    }

}
