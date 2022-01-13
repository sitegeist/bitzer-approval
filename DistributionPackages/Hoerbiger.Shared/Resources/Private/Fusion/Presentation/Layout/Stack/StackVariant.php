<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stack;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class StackVariant implements PseudoEnumInterface
{
    const VARIANT_NOGAP = 'noGap';
    const VARIANT_INLINE = 'inline';
    const VARIANT_XS = 'xs';
    const VARIANT_XS_STRETCH = 'xs_stretch';
    const VARIANT_SM = 'sm';
    const VARIANT_MD = 'md';
    const VARIANT_LG = 'lg';
    const VARIANT_XL = 'xl';

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
            if ($string !== self::VARIANT_NOGAP
                && $string !== self::VARIANT_INLINE
                && $string !== self::VARIANT_XS
                && $string !== self::VARIANT_XS_STRETCH
                && $string !== self::VARIANT_SM
                && $string !== self::VARIANT_MD
                && $string !== self::VARIANT_LG
                && $string !== self::VARIANT_XL) {
                throw StackVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function noGap(): self
    {
        return self::from(self::VARIANT_NOGAP);
    }

    public static function inline(): self
    {
        return self::from(self::VARIANT_INLINE);
    }

    public static function xs(): self
    {
        return self::from(self::VARIANT_XS);
    }

    public static function xsStretch(): self
    {
        return self::from(self::VARIANT_XS_STRETCH);
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

    public static function xl(): self
    {
        return self::from(self::VARIANT_XL);
    }

    public function getIsNoGap(): bool
    {
        return $this->value === self::VARIANT_NOGAP;
    }

    public function getIsInline(): bool
    {
        return $this->value === self::VARIANT_INLINE;
    }

    public function getIsXs(): bool
    {
        return $this->value === self::VARIANT_XS;
    }

    public function getIsXsStretch(): bool
    {
        return $this->value === self::VARIANT_XS_STRETCH;
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

    public function getIsXl(): bool
    {
        return $this->value === self::VARIANT_XL;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_NOGAP),
            self::from(self::VARIANT_INLINE),
            self::from(self::VARIANT_XS),
            self::from(self::VARIANT_XS_STRETCH),
            self::from(self::VARIANT_SM),
            self::from(self::VARIANT_MD),
            self::from(self::VARIANT_LG),
            self::from(self::VARIANT_XL)
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
