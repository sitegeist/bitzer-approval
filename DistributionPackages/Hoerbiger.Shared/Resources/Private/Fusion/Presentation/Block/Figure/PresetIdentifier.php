<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class PresetIdentifier implements PseudoEnumInterface
{
    const IDENTIFIER_XS = 'xs';
    const IDENTIFIER_SM = 'sm';
    const IDENTIFIER_MD = 'md';
    const IDENTIFIER_LG = 'lg';
    const IDENTIFIER_XL = 'xl';

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
            if ($string !== self::IDENTIFIER_XS
                && $string !== self::IDENTIFIER_SM
                && $string !== self::IDENTIFIER_MD
                && $string !== self::IDENTIFIER_LG
                && $string !== self::IDENTIFIER_XL) {
                throw PresetIdentifierIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function xs(): self
    {
        return self::from(self::IDENTIFIER_XS);
    }

    public static function sm(): self
    {
        return self::from(self::IDENTIFIER_SM);
    }

    public static function md(): self
    {
        return self::from(self::IDENTIFIER_MD);
    }

    public static function lg(): self
    {
        return self::from(self::IDENTIFIER_LG);
    }

    public static function xl(): self
    {
        return self::from(self::IDENTIFIER_XL);
    }

    public function getIsXs(): bool
    {
        return $this->value === self::IDENTIFIER_XS;
    }

    public function getIsSm(): bool
    {
        return $this->value === self::IDENTIFIER_SM;
    }

    public function getIsMd(): bool
    {
        return $this->value === self::IDENTIFIER_MD;
    }

    public function getIsLg(): bool
    {
        return $this->value === self::IDENTIFIER_LG;
    }

    public function getIsXl(): bool
    {
        return $this->value === self::IDENTIFIER_XL;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::IDENTIFIER_XS),
            self::from(self::IDENTIFIER_SM),
            self::from(self::IDENTIFIER_MD),
            self::from(self::IDENTIFIER_LG),
            self::from(self::IDENTIFIER_XL)
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
