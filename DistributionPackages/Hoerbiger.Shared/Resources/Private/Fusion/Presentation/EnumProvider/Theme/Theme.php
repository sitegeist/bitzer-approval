<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Theme;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class Theme implements PseudoEnumInterface
{
    const VALUE_EXCITE = 'excite';
    const VALUE_EXPERIENCE = 'experience';
    const VALUE_LITE = 'lite';
    const VALUE_SERVICE = 'service';
    const VALUE_DEFAULT = 'default';
    const VALUE_SLIDER = 'slider';
    const VALUE_SIMPLE_LOOP = 'simpleLoop';

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
            if ($string !== self::VALUE_EXCITE
                && $string !== self::VALUE_EXPERIENCE
                && $string !== self::VALUE_LITE
                && $string !== self::VALUE_SERVICE
                && $string !== self::VALUE_SLIDER
                && $string !== self::VALUE_DEFAULT
                && $string !== self::VALUE_SIMPLE_LOOP) {
                throw ThemeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function excite(): self
    {
        return self::from(self::VALUE_EXCITE);
    }

    public static function experience(): self
    {
        return self::from(self::VALUE_EXPERIENCE);
    }

    public static function lite(): self
    {
        return self::from(self::VALUE_LITE);
    }

    public static function service(): self
    {
        return self::from(self::VALUE_SERVICE);
    }

    public static function slider(): self
    {
        return self::from(self::VALUE_SLIDER);
    }

    public static function default(): self
    {
        return self::from(self::VALUE_DEFAULT);
    }

    public static function simpleLoop(): self
    {
        return self::from(self::VALUE_SIMPLE_LOOP);
    }

    public function getIsExcite(): bool
    {
        return $this->value === self::VALUE_EXCITE;
    }

    public function getIsExperience(): bool
    {
        return $this->value === self::VALUE_EXPERIENCE;
    }

    public function getIsLite(): bool
    {
        return $this->value === self::VALUE_LITE;
    }

    public function getIsService(): bool
    {
        return $this->value === self::VALUE_SERVICE;
    }

    public function getIsSlider(): bool
    {
        return $this->value === self::VALUE_SLIDER;
    }

    public function getIsDefault(): bool
    {
        return $this->value === self::VALUE_DEFAULT;
    }

    public function getIsSimpleLoop(): bool
    {
        return $this->value === self::VALUE_SIMPLE_LOOP;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_EXCITE),
            self::from(self::VALUE_EXPERIENCE),
            self::from(self::VALUE_LITE),
            self::from(self::VALUE_SERVICE),
            self::from(self::VALUE_SLIDER),
            self::from(self::VALUE_DEFAULT),
            self::from(self::VALUE_SIMPLE_LOOP)
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
