<?php declare(strict_types=1);
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
    const VARIANT_DECORATIVEEXCITE = 'decorativeExcite';
    const VARIANT_DECORATIVEEXPERIENCE = 'decorativeExperience';
    const VARIANT_DECORATIVELITE = 'decorativeLite';
    const VARIANT_DECORATIVESERVICE = 'decorativeService';

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
            if ($string !== self::VARIANT_TEXTCENTER
                && $string !== self::VARIANT_NEUTRAL
                && $string !== self::VARIANT_DECORATIVE
                && $string !== self::VARIANT_DECORATIVEEXCITE
                && $string !== self::VARIANT_DECORATIVEEXPERIENCE
                && $string !== self::VARIANT_DECORATIVELITE
                && $string !== self::VARIANT_DECORATIVESERVICE) {
                throw BoxVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme): ?self
    {
        return $theme
            ? self::from('decorative' . (ucfirst($theme->getValue())))
            : null;
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

    public static function decorativeExcite(): self
    {
        return self::from(self::VARIANT_DECORATIVEEXCITE);
    }

    public static function decorativeExperience(): self
    {
        return self::from(self::VARIANT_DECORATIVEEXPERIENCE);
    }

    public static function decorativeLite(): self
    {
        return self::from(self::VARIANT_DECORATIVELITE);
    }

    public static function decorativeService(): self
    {
        return self::from(self::VARIANT_DECORATIVESERVICE);
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

    public function getIsDecorativeExcite(): bool
    {
        return $this->value === self::VARIANT_DECORATIVEEXCITE;
    }

    public function getIsDecorativeExperience(): bool
    {
        return $this->value === self::VARIANT_DECORATIVEEXPERIENCE;
    }

    public function getIsDecorativeLite(): bool
    {
        return $this->value === self::VARIANT_DECORATIVELITE;
    }

    public function getIsDecorativeService(): bool
    {
        return $this->value === self::VARIANT_DECORATIVESERVICE;
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
            self::from(self::VARIANT_DECORATIVEEXCITE),
            self::from(self::VARIANT_DECORATIVEEXPERIENCE),
            self::from(self::VARIANT_DECORATIVELITE),
            self::from(self::VARIANT_DECORATIVESERVICE)
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
