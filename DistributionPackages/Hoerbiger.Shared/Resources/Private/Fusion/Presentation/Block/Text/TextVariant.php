<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class TextVariant implements PseudoEnumInterface
{
    const VARIANT_DEFAULT = 'default';
    const VARIANT_CENTERED = 'centered';
    const VARIANT_LIGHT_CONTRAST = 'lightContrast';
    const VARIANT_BRAND_CONTRAST = 'brandContrast';
    const VARIANT_NEUTRAL_CONTRAST = 'neutralContrast';
    const VARIANT_TRANSPARENT_CONTRAST = 'transparentContrast';
    const VARIANT_EXCITE = 'excite';
    const VARIANT_EXCITE_CONTRAST = 'exciteContrast';
    const VARIANT_EXPERIENCE = 'experience';
    const VARIANT_EXPERIENCE_CONTRAST = 'experienceContrast';
    const VARIANT_LITE = 'lite';
    const VARIANT_LITE_CONTRAST = 'liteContrast';
    const VARIANT_SERVICE_CONTRAST = 'serviceContrast';
    const VARIANT_SERVICE_RED = 'serviceRed';
    const VARIANT_SM = 'sm';

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
                $string !== self::VARIANT_DEFAULT
                && $string !== self::VARIANT_CENTERED
                && $string !== self::VARIANT_LIGHT_CONTRAST
                && $string !== self::VARIANT_BRAND_CONTRAST
                && $string !== self::VARIANT_NEUTRAL_CONTRAST
                && $string !== self::VARIANT_TRANSPARENT_CONTRAST
                && $string !== self::VARIANT_EXCITE
                && $string !== self::VARIANT_EXCITE_CONTRAST
                && $string !== self::VARIANT_EXPERIENCE
                && $string !== self::VARIANT_EXPERIENCE_CONTRAST
                && $string !== self::VARIANT_LITE
                && $string !== self::VARIANT_LITE_CONTRAST
                && $string !== self::VARIANT_SERVICE_CONTRAST
                && $string !== self::VARIANT_SERVICE_RED
                && $string !== self::VARIANT_SM
            ) {
                throw TextVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme, bool $contrast): ?self
    {

        if ($theme) {
            switch ($theme->getValue()) {
                case Theme::VALUE_SERVICE:
                    return self::from($theme->getValue() . 'Contrast');
                case Theme::VALUE_DEFAULT:
                    return self::default();
                default:
                    return self::from($theme->getValue() . ($contrast ? 'Contrast' : ''));
            }
        }
        return null;
    }

    public static function default(): self
    {
        return self::from(self::VARIANT_DEFAULT);
    }

    public static function centered(): self
    {
        return self::from(self::VARIANT_CENTERED);
    }

    public static function lightContrast(): self
    {
        return self::from(self::VARIANT_LIGHT_CONTRAST);
    }

    public static function brandContrast(): self
    {
        return self::from(self::VARIANT_BRAND_CONTRAST);
    }

    public static function neutralContrast(): self
    {
        return self::from(self::VARIANT_NEUTRAL_CONTRAST);
    }

    public static function transparentContrast(): self
    {
        return self::from(self::VARIANT_TRANSPARENT_CONTRAST);
    }

    public static function excite(): self
    {
        return self::from(self::VARIANT_EXCITE);
    }

    public static function exciteContrast(): self
    {
        return self::from(self::VARIANT_EXCITE_CONTRAST);
    }

    public static function experience(): self
    {
        return self::from(self::VARIANT_EXPERIENCE);
    }

    public static function experienceContrast(): self
    {
        return self::from(self::VARIANT_EXPERIENCE_CONTRAST);
    }

    public static function lite(): self
    {
        return self::from(self::VARIANT_LITE);
    }

    public static function liteContrast(): self
    {
        return self::from(self::VARIANT_LITE_CONTRAST);
    }

    public static function serviceContrast(): self
    {
        return self::from(self::VARIANT_SERVICE_CONTRAST);
    }

    public static function serviceRed(): self
    {
        return self::from(self::VARIANT_SERVICE_RED);
    }

    public static function sm(): self
    {
        return self::from(self::VARIANT_SM);
    }

    public function getIsDefault(): bool
    {
        return $this->value === self::VARIANT_DEFAULT;
    }

    public function getIsCentered(): bool
    {
        return $this->value === self::VARIANT_CENTERED;
    }

    public function getIsLightContrast(): bool
    {
        return $this->value === self::VARIANT_LIGHT_CONTRAST;
    }

    public function getIsBrandContrast(): bool
    {
        return $this->value === self::VARIANT_BRAND_CONTRAST;
    }

    public function getIsNeutralContrast(): bool
    {
        return $this->value === self::VARIANT_NEUTRAL_CONTRAST;
    }

    public function getIsTransparentContrast(): bool
    {
        return $this->value === self::VARIANT_TRANSPARENT_CONTRAST;
    }

    public function getIsExcite(): bool
    {
        return $this->value === self::VARIANT_EXCITE;
    }

    public function getIsExciteContrast(): bool
    {
        return $this->value === self::VARIANT_EXCITE_CONTRAST;
    }

    public function getIsExperience(): bool
    {
        return $this->value === self::VARIANT_EXPERIENCE;
    }

    public function getIsExperienceContrast(): bool
    {
        return $this->value === self::VARIANT_EXPERIENCE_CONTRAST;
    }

    public function getIsLite(): bool
    {
        return $this->value === self::VARIANT_LITE;
    }

    public function getIsLiteContrast(): bool
    {
        return $this->value === self::VARIANT_LITE_CONTRAST;
    }

    public function getIsServiceContrast(): bool
    {
        return $this->value === self::VARIANT_SERVICE_CONTRAST;
    }

    public function getIsServiceRed(): bool
    {
        return $this->value === self::VARIANT_SERVICE_RED;
    }

    public function getIsSm(): bool
    {
        return $this->value === self::VARIANT_SM;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_DEFAULT),
            self::from(self::VARIANT_CENTERED),
            self::from(self::VARIANT_LIGHT_CONTRAST),
            self::from(self::VARIANT_BRAND_CONTRAST),
            self::from(self::VARIANT_NEUTRAL_CONTRAST),
            self::from(self::VARIANT_TRANSPARENT_CONTRAST),
            self::from(self::VARIANT_EXCITE),
            self::from(self::VARIANT_EXCITE_CONTRAST),
            self::from(self::VARIANT_EXPERIENCE),
            self::from(self::VARIANT_EXPERIENCE_CONTRAST),
            self::from(self::VARIANT_LITE),
            self::from(self::VARIANT_LITE_CONTRAST),
            self::from(self::VARIANT_SERVICE_CONTRAST),
            self::from(self::VARIANT_SERVICE_RED),
            self::from(self::VARIANT_SM)
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
