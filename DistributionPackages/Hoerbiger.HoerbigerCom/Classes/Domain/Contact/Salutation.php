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
final class Salutation implements PseudoEnumInterface, \JsonSerializable
{
    const VALUE_MR = 'Mr.';
    const VALUE_MS = 'Ms.';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::VALUE_MR
                && $string !== self::VALUE_MS) {
                throw CustomerGroupIdentifierIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function mr(): self
    {
        return self::from(self::VALUE_MR);
    }

    public static function ms(): self
    {
        return self::from(self::VALUE_MS);
    }

    public function getIsMr(): bool
    {
        return $this->value === self::VALUE_MR;
    }

    public function getIsMs(): bool
    {
        return $this->value === self::VALUE_MS;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_MR),
            self::from(self::VALUE_MS)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
