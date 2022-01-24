<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class HeadlineVariant implements PseudoEnumInterface
{
    const VARIANT_LARGEBRAND = 'largeBrand';
    const VARIANT_LARGEWHITE = 'largeWhite';
    const VARIANT_LARGE = 'large';
    const VARIANT_MEDIUMBRAND = 'mediumBrand';
    const VARIANT_MEDIUMWHITE = 'mediumWhite';
    const VARIANT_MEDIUM = 'medium';

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
            if ($string !== self::VARIANT_LARGEBRAND
                && $string !== self::VARIANT_LARGEWHITE
                && $string !== self::VARIANT_LARGE
                && $string !== self::VARIANT_MEDIUMBRAND
                && $string !== self::VARIANT_MEDIUMWHITE
                && $string !== self::VARIANT_MEDIUM) {
                throw HeadlineVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function largeBrand(): self
    {
        return self::from(self::VARIANT_LARGEBRAND);
    }

    public static function largeWhite(): self
    {
        return self::from(self::VARIANT_LARGEWHITE);
    }

    public static function large(): self
    {
        return self::from(self::VARIANT_LARGE);
    }

    public static function mediumBrand(): self
    {
        return self::from(self::VARIANT_MEDIUMBRAND);
    }

    public static function mediumWhite(): self
    {
        return self::from(self::VARIANT_MEDIUMWHITE);
    }

    public static function medium(): self
    {
        return self::from(self::VARIANT_MEDIUM);
    }

    public function getIsLargeBrand(): bool
    {
        return $this->value === self::VARIANT_LARGEBRAND;
    }

    public function getIsLargeWhite(): bool
    {
        return $this->value === self::VARIANT_LARGEWHITE;
    }

    public function getIsLarge(): bool
    {
        return $this->value === self::VARIANT_LARGE;
    }

    public function getIsMediumBrand(): bool
    {
        return $this->value === self::VARIANT_MEDIUMBRAND;
    }

    public function getIsMediumWhite(): bool
    {
        return $this->value === self::VARIANT_MEDIUMWHITE;
    }

    public function getIsMedium(): bool
    {
        return $this->value === self::VARIANT_MEDIUM;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_LARGEBRAND),
            self::from(self::VARIANT_LARGEWHITE),
            self::from(self::VARIANT_LARGE),
            self::from(self::VARIANT_MEDIUMBRAND),
            self::from(self::VARIANT_MEDIUMWHITE),
            self::from(self::VARIANT_MEDIUM)
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
