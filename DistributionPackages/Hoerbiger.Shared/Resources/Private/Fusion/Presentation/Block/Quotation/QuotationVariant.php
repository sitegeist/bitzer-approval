<?php declare(strict_types=1);
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
    const VARIANT_REGULAREXCITE = 'regularExcite';
    const VARIANT_REGULAREXPERIENCE = 'regularExperience';
    const VARIANT_REGULARLITE = 'regularLite';
    const VARIANT_REGULARSERVICE = 'regularService';

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
            if ($string !== self::VARIANT_REGULAR
                && $string !== self::VARIANT_INLINE
                && $string !== self::VARIANT_REGULAREXCITE
                && $string !== self::VARIANT_REGULAREXPERIENCE
                && $string !== self::VARIANT_REGULARLITE
                && $string !== self::VARIANT_REGULARSERVICE) {
                throw QuotationVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme): ?self
    {
        return $theme
            ? self::from('regular' . (ucfirst($theme->getValue())))
            : null;
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
        return self::from(self::VARIANT_REGULAREXCITE);
    }

    public static function regularExperience(): self
    {
        return self::from(self::VARIANT_REGULAREXPERIENCE);
    }

    public static function regularLite(): self
    {
        return self::from(self::VARIANT_REGULARLITE);
    }

    public static function regularService(): self
    {
        return self::from(self::VARIANT_REGULARSERVICE);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsInline(): bool
    {
        return $this->value === self::VARIANT_INLINE;
    }

    public function getIsRegularExcite(): bool
    {
        return $this->value === self::VARIANT_REGULAREXCITE;
    }

    public function getIsRegularExperience(): bool
    {
        return $this->value === self::VARIANT_REGULAREXPERIENCE;
    }

    public function getIsRegularLite(): bool
    {
        return $this->value === self::VARIANT_REGULARLITE;
    }

    public function getIsRegularService(): bool
    {
        return $this->value === self::VARIANT_REGULARSERVICE;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_INLINE),
            self::from(self::VARIANT_REGULAREXCITE),
            self::from(self::VARIANT_REGULAREXPERIENCE),
            self::from(self::VARIANT_REGULARLITE),
            self::from(self::VARIANT_REGULARSERVICE)
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
