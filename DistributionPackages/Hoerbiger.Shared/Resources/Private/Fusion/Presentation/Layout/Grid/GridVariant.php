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
    const VARIANT_TWOCOLUMNSNOGAP = 'twoColumnsNoGap';
    const VARIANT_TWOCOLUMNSGAP = 'twoColumnsGap';
    const VARIANT_THREECOLUMNSGAP = 'threeColumnsGap';
    const VARIANT_THREECOLUMNSNARROWGAP = 'threeColumnsnNarrowGap';
    const VARIANT_TWOCOLUMNSFRMAXGAP = 'twoColumnsFrMaxGap';
    const VARIANT_TWOCOLUMNSTHIRDAUTOGAP = 'twoColumnsThirdAutoGap';
    const VARIANT_TWOCOLUMNSTHIRDBIGGAP = 'twoColumnsThirdBigGap';
    const VARIANT_TWOCOLUMNSPRESS = 'twoColumnsPress';
    const VARIANT_ICONLEFT = 'iconLeft';
    const VARIANT_ICONRIGHT = 'iconRight';

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
                && $string !== self::VARIANT_TWOCOLUMNSNOGAP
                && $string !== self::VARIANT_TWOCOLUMNSGAP
                && $string !== self::VARIANT_THREECOLUMNSGAP
                && $string !== self::VARIANT_THREECOLUMNSNARROWGAP
                && $string !== self::VARIANT_TWOCOLUMNSFRMAXGAP
                && $string !== self::VARIANT_TWOCOLUMNSTHIRDAUTOGAP
                && $string !== self::VARIANT_TWOCOLUMNSTHIRDBIGGAP
                && $string !== self::VARIANT_TWOCOLUMNSPRESS
                && $string !== self::VARIANT_ICONLEFT
                && $string !== self::VARIANT_ICONRIGHT) {
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

    public static function twoColumnsNoGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSNOGAP);
    }

    public static function twoColumnsGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSGAP);
    }

    public static function threeColumnsGap(): self
    {
        return self::from(self::VARIANT_THREECOLUMNSGAP);
    }

    public static function threeColumnsNarrowGap(): self
    {
        return self::from(self::VARIANT_THREECOLUMNSNARROWGAP);
    }

    public static function twoColumnsFrMaxGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSFRMAXGAP);
    }

    public static function twoColumnsThirdAutoGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSTHIRDAUTOGAP);
    }

    public static function twoColumnsThirdBigGap(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSTHIRDBIGGAP);
    }

    public static function twoColumnsPress(): self
    {
        return self::from(self::VARIANT_TWOCOLUMNSPRESS);
    }

    public static function iconLeft(): self
    {
        return self::from(self::VARIANT_ICONLEFT);
    }

    public static function iconRight(): self
    {
        return self::from(self::VARIANT_ICONRIGHT);
    }

    public function getIsOneColumn(): bool
    {
        return $this->value === self::VARIANT_ONECOLUMN;
    }

    public function getIsTwoColumns(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNS;
    }

    public function getIsTwoColumnsNoGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSNOGAP;
    }

    public function getIsTwoColumnsGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSGAP;
    }

    public function getIsThreeColumnsGap(): bool
    {
        return $this->value === self::VARIANT_THREECOLUMNSGAP;
    }

    public function getIsThreeColumnsNarrowGap(): bool
    {
        return $this->value === self::VARIANT_THREECOLUMNSNARROWGAP;
    }

    public function getIsTwoColumnsFrMaxGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSFRMAXGAP;
    }

    public function getIsTwoColumnsThirdAutoGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSTHIRDAUTOGAP;
    }

    public function getIsTwoColumnsThirdBigGap(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSTHIRDBIGGAP;
    }

    public function getIsTwoColumnsPress(): bool
    {
        return $this->value === self::VARIANT_TWOCOLUMNSPRESS;
    }

    public function getIsIconLeft(): bool
    {
        return $this->value === self::VARIANT_ICONLEFT;
    }

    public function getIsIconRight(): bool
    {
        return $this->value === self::VARIANT_ICONRIGHT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_ONECOLUMN),
            self::from(self::VARIANT_TWOCOLUMNS),
            self::from(self::VARIANT_TWOCOLUMNSNOGAP),
            self::from(self::VARIANT_TWOCOLUMNSGAP),
            self::from(self::VARIANT_THREECOLUMNSGAP),
            self::from(self::VARIANT_THREECOLUMNSNARROWGAP),
            self::from(self::VARIANT_TWOCOLUMNSFRMAXGAP),
            self::from(self::VARIANT_TWOCOLUMNSTHIRDAUTOGAP),
            self::from(self::VARIANT_TWOCOLUMNSTHIRDBIGGAP),
            self::from(self::VARIANT_TWOCOLUMNSPRESS),
            self::from(self::VARIANT_ICONLEFT),
            self::from(self::VARIANT_ICONRIGHT)
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
