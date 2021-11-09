<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Row;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class RowVariant implements PseudoEnumInterface
{
    const VARIANT_CH = 'ch';
    const VARIANT_XS = 'xs';
    const VARIANT_SM = 'sm';
    const VARIANT_MD = 'md';
    const VARIANT_LG = 'lg';
    const VARIANT_GALLERY = 'gallery';
    const VARIANT_ALTERNATEMETALINE = 'alternateMetaline';
    const VARIANT_ALTERNATEMETALINE_ELLIPSIS = 'alternateMetalineEllipsis';

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
            if ($string !== self::VARIANT_CH
                && $string !== self::VARIANT_XS
                && $string !== self::VARIANT_SM
                && $string !== self::VARIANT_MD
                && $string !== self::VARIANT_LG
                && $string !== self::VARIANT_GALLERY
                && $string !== self::VARIANT_ALTERNATEMETALINE
                && $string !== self::VARIANT_ALTERNATEMETALINE_ELLIPSIS) {
                throw RowVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function ch(): self
    {
        return self::from(self::VARIANT_CH);
    }

    public static function xs(): self
    {
        return self::from(self::VARIANT_XS);
    }

    public static function sm(): self
    {
        return self::from(self::VARIANT_SM);
    }

    public static function md(): self
    {
        return self::from(self::VARIANT_MD);
    }

    public static function lg(): self
    {
        return self::from(self::VARIANT_LG);
    }

    public static function gallery(): self
    {
        return self::from(self::VARIANT_GALLERY);
    }

    public static function alternateMetaline(): self
    {
        return self::from(self::VARIANT_ALTERNATEMETALINE);
    }

    public static function alternateMetalineEllipsis(): self
    {
        return self::from(self::VARIANT_ALTERNATEMETALINE_ELLIPSIS);
    }

    public function getIsCh(): bool
    {
        return $this->value === self::VARIANT_CH;
    }

    public function getIsXs(): bool
    {
        return $this->value === self::VARIANT_XS;
    }

    public function getIsSm(): bool
    {
        return $this->value === self::VARIANT_SM;
    }

    public function getIsMd(): bool
    {
        return $this->value === self::VARIANT_MD;
    }

    public function getIsLg(): bool
    {
        return $this->value === self::VARIANT_LG;
    }

    public function getIsGallery(): bool
    {
        return $this->value === self::VARIANT_GALLERY;
    }

    public function getIsAlternateMetaline(): bool
    {
        return $this->value === self::VARIANT_ALTERNATEMETALINE;
    }

    public function getIsAlternateMetalineEllipsis(): bool
    {
        return $this->value === self::VARIANT_ALTERNATEMETALINE_ELLIPSIS;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_CH),
            self::from(self::VARIANT_XS),
            self::from(self::VARIANT_SM),
            self::from(self::VARIANT_MD),
            self::from(self::VARIANT_LG),
            self::from(self::VARIANT_GALLERY),
            self::from(self::VARIANT_ALTERNATEMETALINE),
            self::from(self::VARIANT_ALTERNATEMETALINE_ELLIPSIS)
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
