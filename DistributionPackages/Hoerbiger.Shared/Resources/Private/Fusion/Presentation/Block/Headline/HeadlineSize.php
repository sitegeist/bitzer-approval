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
final class HeadlineSize implements PseudoEnumInterface
{
    const SIZE_SUPER_LARGE = 'superLarge';
    const SIZE_SUPER_LARGE_LIGHT = 'superLargeLight';
    const SIZE_EXTRA_LARGE = 'extraLarge';
    const SIZE_LARGE = 'large';
    const SIZE_LARGE_CENTERED = 'largeCentered';
    const SIZE_MEDIUM = 'medium';
    const SIZE_MEDIUM_ITALIC = 'mediumItalic';
    const SIZE_SMALL = 'small';
    const SIZE_VERY_SMALL = 'verySmall';
    const SIZE_VERY_SMALL_ITALIC = 'verySmallItalic';
    const SIZE_VERY_SMALL_REGULAR = 'verySmallRegular';
    const SIZE_TILE = 'tile';
    const SIZE_TINY = 'tiny';


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
            if ($string !== self::SIZE_SUPER_LARGE
                && $string !== self::SIZE_SUPER_LARGE_LIGHT
                && $string !== self::SIZE_EXTRA_LARGE
                && $string !== self::SIZE_LARGE
                && $string !== self::SIZE_LARGE_CENTERED
                && $string !== self::SIZE_MEDIUM
                && $string !== self::SIZE_MEDIUM_ITALIC
                && $string !== self::SIZE_SMALL
                && $string !== self::SIZE_VERY_SMALL
                && $string !== self::SIZE_VERY_SMALL_ITALIC
                && $string !== self::SIZE_VERY_SMALL_REGULAR
                && $string !== self::SIZE_TILE
                && $string !== self::SIZE_TINY
                ) {
                throw HeadlineSizeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function superLarge(): self
    {
        return self::from(self::SIZE_SUPER_LARGE);
    }

    public static function superLargeLight(): self
    {
        return self::from(self::SIZE_SUPER_LARGE_LIGHT);
    }

    public static function extraLarge(): self
    {
        return self::from(self::SIZE_EXTRA_LARGE);
    }

    public static function large(): self
    {
        return self::from(self::SIZE_LARGE);
    }

    public static function largeCentered(): self
    {
        return self::from(self::SIZE_LARGE_CENTERED);
    }

    public static function medium(): self
    {
        return self::from(self::SIZE_MEDIUM);
    }

    public static function mediumItalic(): self
    {
        return self::from(self::SIZE_MEDIUM_ITALIC);
    }

    public static function small(): self
    {
        return self::from(self::SIZE_SMALL);
    }

    public static function verySmall(): self
    {
        return self::from(self::SIZE_VERY_SMALL);
    }

    public static function verySmallItalic(): self
    {
        return self::from(self::SIZE_VERY_SMALL_ITALIC);
    }

    public static function verySmallRegular(): self
    {
        return self::from(self::SIZE_VERY_SMALL_REGULAR);
    }

    public static function tile(): self
    {
        return self::from(self::SIZE_TILE);
    }

    public static function tiny(): self
    {
        return self::from(self::SIZE_TINY);
    }

    public function getIsLarge(): bool
    {
        return $this->value === self::SIZE_LARGE;
    }

    public function getIsExtraLarge(): bool
    {
        return $this->value === self::SIZE_EXTRA_LARGE;
    }

    public function getIsSuperLarge(): bool
    {
        return $this->value === self::SIZE_SUPER_LARGE;
    }

    public function getIsSuperLargeLight(): bool
    {
        return $this->value === self::SIZE_SUPER_LARGE_LIGHT;
    }

    public function getIsLargeCentered(): bool
    {
        return $this->value === self::SIZE_LARGE_CENTERED;
    }

    public function getIsMedium(): bool
    {
        return $this->value === self::SIZE_MEDIUM;
    }

    public function getIsMediumItalic(): bool
    {
        return $this->value === self::SIZE_MEDIUM_ITALIC;
    }

    public function getIsSmall(): bool
    {
        return $this->value === self::SIZE_SMALL;
    }

    public function getIsVerySmall(): bool
    {
        return $this->value === self::SIZE_VERY_SMALL;
    }

    public function getIsVerySmallItalic(): bool
    {
        return $this->value === self::SIZE_VERY_SMALL_ITALIC;
    }

    public function getIsVerySmallRegular(): bool
    {
        return $this->value === self::SIZE_VERY_SMALL_REGULAR;
    }

    public function getIsTile(): bool
    {
        return $this->value === self::SIZE_TILE;
    }

    public function getIsTiny(): bool
    {
        return $this->value === self::SIZE_TINY;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::SIZE_EXTRA_LARGE),
            self::from(self::SIZE_SUPER_LARGE),
            self::from(self::SIZE_SUPER_LARGE_LIGHT),
            self::from(self::SIZE_LARGE),
            self::from(self::SIZE_LARGE_CENTERED),
            self::from(self::SIZE_MEDIUM),
            self::from(self::SIZE_MEDIUM_ITALIC),
            self::from(self::SIZE_SMALL),
            self::from(self::SIZE_VERY_SMALL),
            self::from(self::SIZE_VERY_SMALL_REGULAR),
            self::from(self::SIZE_TILE),
            self::from(self::SIZE_TINY)
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
