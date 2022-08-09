<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class BoxVariant implements PseudoEnumInterface
{
    const VARIANT_TEXTCENTER = 'textCenter';
    const VARIANT_NEUTRAL = 'neutral';
    const VARIANT_DECORATIVE = 'decorative';
    const VARIANT_EXCITE = 'excite';
    const VARIANT_EXPERIENCE = 'experience';
    const VARIANT_LITE = 'lite';
    const VARIANT_LIGHT = 'light';
    const VARIANT_SERVICE = 'service';
    const VARIANT_DEFAULT = 'default';

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
                $string !== self::VARIANT_TEXTCENTER
                && $string !== self::VARIANT_NEUTRAL
                && $string !== self::VARIANT_DECORATIVE
                && $string !== self::VARIANT_EXCITE
                && $string !== self::VARIANT_EXPERIENCE
                && $string !== self::VARIANT_LITE
                && $string !== self::VARIANT_LIGHT
                && $string !== self::VARIANT_SERVICE
                && $string !== self::VARIANT_DEFAULT
            ) {
                throw BoxVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme): ?self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::from(self::VARIANT_DECORATIVE);
        }
        return self::from($theme->getValue());
    }

    public static function textCenter(): self
    {
        return self::from(self::VARIANT_TEXTCENTER);
    }

    public static function neutral(): self
    {
        return self::from(self::VARIANT_NEUTRAL);
    }

    public static function decorative(): self
    {
        return self::from(self::VARIANT_DECORATIVE);
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

    public static function light(): self
    {
        return self::from(self::VARIANT_LIGHT);
    }

    public static function service(): self
    {
        return self::from(self::VARIANT_SERVICE);
    }

    public static function default(): self
    {
        return self::from(self::VARIANT_DEFAULT);
    }

    public function getIsTextCenter(): bool
    {
        return $this->value === self::VARIANT_TEXTCENTER;
    }

    public function getIsNeutral(): bool
    {
        return $this->value === self::VARIANT_NEUTRAL;
    }

    public function getIsDecorative(): bool
    {
        return $this->value === self::VARIANT_DECORATIVE;
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

    public function getIsLight(): bool
    {
        return $this->value === self::VARIANT_LIGHT;
    }

    public function getIsService(): bool
    {
        return $this->value === self::VARIANT_SERVICE;
    }

    public function getIsDefault(): bool
    {
        return $this->value === self::VARIANT_DEFAULT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_TEXTCENTER),
            self::from(self::VARIANT_NEUTRAL),
            self::from(self::VARIANT_DECORATIVE),
            self::from(self::VARIANT_EXCITE),
            self::from(self::VARIANT_EXPERIENCE),
            self::from(self::VARIANT_LITE),
            self::from(self::VARIANT_LIGHT),
            self::from(self::VARIANT_SERVICE),
            self::from(self::VARIANT_DEFAULT)
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
