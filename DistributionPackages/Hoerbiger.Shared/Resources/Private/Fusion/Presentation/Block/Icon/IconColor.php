<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class IconColor implements PseudoEnumInterface
{
    const COLOR_CURRENT = 'current';
    const COLOR_BRAND = 'brand';
    const COLOR_WHITE = 'white';
    const COLOR_TEXT = 'text';
    const COLOR_EXCITE = 'excite';
    const COLOR_EXCITE_CONTRAST = 'exciteContrast';
    const COLOR_EXPERIENCE = 'experience';
    const COLOR_EXPERIENCE_CONTRAST = 'experienceContrast';
    const COLOR_LITE = 'lite';
    const COLOR_LITE_CONTRAST = 'liteContrast';
    const COLOR_SERVICE_CONTRAST = 'serviceContrast';

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
            if ($string !== self::COLOR_BRAND
                && $string !== self::COLOR_CURRENT
                && $string !== self::COLOR_WHITE
                && $string !== self::COLOR_TEXT
                && $string !== self::COLOR_EXCITE
                && $string !== self::COLOR_EXCITE_CONTRAST
                && $string !== self::COLOR_EXPERIENCE
                && $string !== self::COLOR_EXPERIENCE_CONTRAST
                && $string !== self::COLOR_LITE
                && $string !== self::COLOR_LITE_CONTRAST
                && $string !== self::COLOR_SERVICE_CONTRAST) {
                throw IconColorIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme, bool $contrast): ?self
    {
        return $theme
            ? self::from(
                $theme->getIsService()
                    ? $theme->getValue() . 'Contrast'
                    : $theme->getValue() . ($contrast ? 'Contrast' : ''))
            : null;
    }

    public static function brand(): self
    {
        return self::from(self::COLOR_BRAND);
    }

    public static function current(): self
    {
        return self::from(self::COLOR_CURRENT);
    }

    public static function white(): self
    {
        return self::from(self::COLOR_WHITE);
    }

    public static function text(): self
    {
        return self::from(self::COLOR_TEXT);
    }

    public static function excite(): self
    {
        return self::from(self::COLOR_EXCITE);
    }

    public static function exciteContrast(): self
    {
        return self::from(self::COLOR_EXCITE_CONTRAST);
    }

    public static function experience(): self
    {
        return self::from(self::COLOR_EXPERIENCE);
    }

    public static function experienceContrast(): self
    {
        return self::from(self::COLOR_EXPERIENCE_CONTRAST);
    }

    public static function lite(): self
    {
        return self::from(self::COLOR_LITE);
    }

    public static function liteContrast(): self
    {
        return self::from(self::COLOR_LITE_CONTRAST);
    }

    public static function serviceContrast(): self
    {
        return self::from(self::COLOR_SERVICE_CONTRAST);
    }

    public function getIsBrand(): bool
    {
        return $this->value === self::COLOR_BRAND;
    }
    public function getIsCurrent(): bool
    {
        return $this->value === self::COLOR_CURRENT;
    }

    public function getIsWhite(): bool
    {
        return $this->value === self::COLOR_WHITE;
    }

    public function getIsText(): bool
    {
        return $this->value === self::COLOR_TEXT;
    }

    public function getIsExcite(): bool
    {
        return $this->value === self::COLOR_EXCITE;
    }

    public function getIsExciteContrast(): bool
    {
        return $this->value === self::COLOR_EXCITE_CONTRAST;
    }

    public function getIsExperience(): bool
    {
        return $this->value === self::COLOR_EXPERIENCE;
    }

    public function getIsExperienceContrast(): bool
    {
        return $this->value === self::COLOR_EXPERIENCE_CONTRAST;
    }

    public function getIsLite(): bool
    {
        return $this->value === self::COLOR_LITE;
    }

    public function getIsLiteContrast(): bool
    {
        return $this->value === self::COLOR_LITE_CONTRAST;
    }

    public function getIsServiceContrast(): bool
    {
        return $this->value === self::COLOR_SERVICE_CONTRAST;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::COLOR_CURRENT),
            self::from(self::COLOR_BRAND),
            self::from(self::COLOR_WHITE),
            self::from(self::COLOR_TEXT),
            self::from(self::COLOR_EXCITE),
            self::from(self::COLOR_EXCITE_CONTRAST),
            self::from(self::COLOR_EXPERIENCE),
            self::from(self::COLOR_EXPERIENCE_CONTRAST),
            self::from(self::COLOR_LITE),
            self::from(self::COLOR_LITE_CONTRAST),
            self::from(self::COLOR_SERVICE_CONTRAST)
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
