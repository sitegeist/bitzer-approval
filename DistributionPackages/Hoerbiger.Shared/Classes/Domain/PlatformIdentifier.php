<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;

enum PlatformIdentifier: string implements \JsonSerializable
{
    case IDENTIFIER_YOUTUBE = 'YouTube';
    case IDENTIFIER_VIMEO = 'Vimeo';

    public function renderUri(string $identifier): Uri
    {
        return match ($this) {
            self::IDENTIFIER_VIMEO
            => new Uri('https://player.vimeo.com/video/' . $identifier . '?autoplay=1&autopause=0'),
            default => new Uri('https://www.youtube.com/embed/' . $identifier . '?controls=2&rel=0&autoplay=1'),
        };
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
