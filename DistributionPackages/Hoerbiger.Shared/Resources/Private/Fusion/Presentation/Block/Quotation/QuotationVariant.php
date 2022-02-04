<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Quotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class QuotationVariant implements PseudoEnumInterface
{
    const VARIANT_REGULAR = 'regular';
    const VARIANT_INLINE = 'inline';
    const VARIANT_EXCITE = 'excite';
    const VARIANT_EXPERIENCE = 'experience';
    const VARIANT_LITE = 'lite';
    const VARIANT_SERVICE = 'service';

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
                $string !== self::VARIANT_REGULAR
                && $string !== self::VARIANT_INLINE
                && $string !== self::VARIANT_EXCITE
                && $string !== self::VARIANT_EXPERIENCE
                && $string !== self::VARIANT_LITE
                && $string !== self::VARIANT_SERVICE
            ) {
                throw QuotationVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme): self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::from(self::VARIANT_REGULAR);
        }
        return self::from($theme->getValue());
    }

    public static function regular(): self
    {
        return self::from(self::VARIANT_REGULAR);
    }

    public static function inline(): self
    {
        return self::from(self::VARIANT_INLINE);
    }

    public static function regularExcite(): self
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

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsInline(): bool
    {
        return $this->value === self::VARIANT_INLINE;
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

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_INLINE),
            self::from(self::VARIANT_EXCITE),
            self::from(self::VARIANT_EXPERIENCE),
            self::from(self::VARIANT_LITE),
            self::from(self::VARIANT_SERVICE)
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
