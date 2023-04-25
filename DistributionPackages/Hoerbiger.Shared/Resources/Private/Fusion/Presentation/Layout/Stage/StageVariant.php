<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class StageVariant implements PseudoEnumInterface
{
    const VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    const VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';
    const VARIANT_FULLMEDIATEXTCENTER = 'fullMediaTextCenter';
    const VARIANT_STACK = 'stack';
    const VARIANT_EXCITE = 'excite';
    const VARIANT_EXPERIENCE = 'experience';
    const VARIANT_LITE = 'lite';
    const VARIANT_SERVICE = 'service';
    const VARIANT_DEFAULT = 'default';
    const VARIANT_PRESS = 'press';
    const VARIANT_PRESS_LARGE = 'pressLarge';

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
            if (
                $string !== self::VARIANT_FULLMEDIATEXTLEFT
                && $string !== self::VARIANT_FULLMEDIATEXTRIGHT
                && $string !== self::VARIANT_FULLMEDIATEXTCENTER
                && $string !== self::VARIANT_STACK
                && $string !== self::VARIANT_EXCITE
                && $string !== self::VARIANT_EXPERIENCE
                && $string !== self::VARIANT_LITE
                && $string !== self::VARIANT_SERVICE
                && $string !== self::VARIANT_DEFAULT
                && $string !== self::VARIANT_PRESS
                && $string !== self::VARIANT_PRESS_LARGE
            ) {
                throw StageVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function fromTheme(?Theme $theme): self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::from(self::VARIANT_DEFAULT);
        }
        return self::from($theme->getValue());
    }

    public static function fullMediaTextLeft(): self
    {
        return self::from(self::VARIANT_FULLMEDIATEXTLEFT);
    }

    public static function fullMediaTextRight(): self
    {
        return self::from(self::VARIANT_FULLMEDIATEXTRIGHT);
    }

    public static function fullMediaTextCenter(): self
    {
        return self::from(self::VARIANT_FULLMEDIATEXTCENTER);
    }

    public static function stack(): self
    {
        return self::from(self::VARIANT_STACK);
    }

    public static function excite(): self
    {
        return self::from(self::VARIANT_EXCITE);
    }

    public static function experience(): self
    {
        return self::from(self::VARIANT_EXPERIENCE);
    }

    public static function lite(): self
    {
        return self::from(self::VARIANT_LITE);
    }

    public static function service(): self
    {
        return self::from(self::VARIANT_SERVICE);
    }

    public static function default(): self
    {
        return self::from(self::VARIANT_DEFAULT);
    }

    public static function press(): self
    {
        return self::from(self::VARIANT_PRESS);
    }

    public static function pressLarge(): self
    {
        return self::from(self::VARIANT_PRESS_LARGE);
    }

    public function getIsFullMediaTextLeft(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIATEXTLEFT;
    }

    public function getIsFullMediaTextRight(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIATEXTRIGHT;
    }

    public function getIsFullMediaTextCenter(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIATEXTCENTER;
    }

    public function getIsStack(): bool
    {
        return $this->value === self::VARIANT_STACK;
    }

    public function getIsExcite(): bool
    {
        return $this->value === self::VARIANT_EXCITE;
    }

    public function getIsExperience(): bool
    {
        return $this->value === self::VARIANT_EXPERIENCE;
    }

    public function getIsLite(): bool
    {
        return $this->value === self::VARIANT_LITE;
    }

    public function getIsService(): bool
    {
        return $this->value === self::VARIANT_SERVICE;
    }

    public function getIsDefault(): bool
    {
        return $this->value === self::VARIANT_DEFAULT;
    }

    public function getIsPress(): bool
    {
        return $this->value === self::VARIANT_PRESS;
    }

    public function getIsPressLarge(): bool
    {
        return $this->value === self::VARIANT_PRESS_LARGE;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_FULLMEDIATEXTLEFT),
            self::from(self::VARIANT_FULLMEDIATEXTRIGHT),
            self::from(self::VARIANT_FULLMEDIATEXTCENTER),
            self::from(self::VARIANT_STACK),
            self::from(self::VARIANT_EXCITE),
            self::from(self::VARIANT_EXPERIENCE),
            self::from(self::VARIANT_LITE),
            self::from(self::VARIANT_SERVICE),
            self::from(self::VARIANT_DEFAULT),
            self::from(self::VARIANT_PRESS),
            self::from(self::VARIANT_PRESS_LARGE)
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
