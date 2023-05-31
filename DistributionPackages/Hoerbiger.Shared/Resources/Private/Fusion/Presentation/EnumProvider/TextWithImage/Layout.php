<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum Layout: string
{
    case VALUE_IMAGEFIRST = 'imageFirst';
    case VALUE_IMAGELAST = 'imageLast';

    public static function fromString(string $string): self
    {
        return match ($string){
            'imageFirst' => self::VALUE_IMAGEFIRST,
            'imageLast' => self::VALUE_IMAGELAST,
            default => null
        };
    }

    public function getIsImageFirst(): bool
    {
        return $this->value === self::VALUE_IMAGEFIRST;
    }

    public function getIsImageLast(): bool
    {
        return $this->value === self::VALUE_IMAGELAST;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_IMAGEFIRST),
            self::from(self::VALUE_IMAGELAST)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
