<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum Salutation: string implements \JsonSerializable
{
    const VALUE_MR = 'Mr.';
    const VALUE_MS = 'Ms.';

    public static function fromString(string $string): self|null
    {
        return match ($string){
            'Mr.' => self::VALUE_MR,
            'Ms.' => self::VALUE_MS,
            default => null
        };
    }

    public static function mr(): self
    {
        return self::fromString(self::VALUE_MR);
    }

    public static function ms(): self
    {
        return self::fromString(self::VALUE_MS);
    }

    public function getIsMr(): bool
    {
        return $this->value === self::VALUE_MR;
    }

    public function getIsMs(): bool
    {
        return $this->value === self::VALUE_MS;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

}
