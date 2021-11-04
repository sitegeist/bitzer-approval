<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Grid;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class GridVariant implements PseudoEnumInterface
{
    const VARIANT_ONECOLUMN = 'oneColumn';
    const VARIANT_TWOCOLUMNS = 'twoColumns';
    const VARIANT_TWOCOLUMNSGAP = 'twoColumnsGap';
    const VARIANT_THREECOLUMNSGAP = 'threeColumnsGap';
    const VARIANT_TWOCOLUMNSFRMAXGAP = 'twoColumnsFrMaxGap';

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
            if ($string !== self::VARIANT_ONECOLUMN
                && $string !== self::VARIANT_TWOCOLUMNS
                && $string !== self::VARIANT_TWOCOLUMNSGAP
                && $string !== self::VARIANT_THREECOLUMNSGAP
                && $string !== self::VARIANT_TWOCOLUMNSFRMAXGAP) {
                throw GridVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function oneColumn(): self
    {
        return self::from(self::VARIANT_ONECOLUMN);
    }

    public static function twoColumns(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNS);
    }

    public static function twoColumnsGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSGAP);
    }

    public static function threeColumnsGap(): self
    {
        return self::from(self::VARIANT_THREECOLUMNSGAP);
    }

    public static function twoColumnsFrMaxGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSFRMAXGAP);
    }

    public function getIsOneColumn(): bool
    {
        return $this->value === self::VARIANT_ONECOLUMN;
    }

    public function getIsTwoColumns(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNS;
    }

    public function getIsTwoColumnsGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSGAP;
    }

    public function getIsThreeColumnsGap(): bool
    {
        return $this->value === self::VARIANT_THREECOLUMNSGAP;
    }

    public function getIsTwoColumnsFrMaxGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSFRMAXGAP;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_ONECOLUMN),
            self::from(self::VARIANT_TWOCOLUMNS),
            self::from(self::VARIANT_TWOCOLUMNSGAP),
            self::from(self::VARIANT_THREECOLUMNSGAP),
            self::from(self::VARIANT_TWOCOLUMNSFRMAXGAP)
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
