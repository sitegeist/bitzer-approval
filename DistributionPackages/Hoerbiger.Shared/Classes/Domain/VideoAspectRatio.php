<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

enum VideoAspectRatio: string implements \JsonSerializable
{
    case RATIO_16_9 = 'ratio_16_9';
    case RATIO_16_10 = 'ratio_16_10';
    case RATIO_4_3 = 'ratio_4_3';

    public static function fromString(string $value): self
    {
        return self::from($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
