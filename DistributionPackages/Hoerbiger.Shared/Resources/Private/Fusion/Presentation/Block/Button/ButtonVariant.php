<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class ButtonVariant implements PseudoEnumInterface
{
    const VARIANT_CTA = 'cta';
    const VARIANT_CTA_CONTRAST = 'ctaContrast';
    const VARIANT_CONTRAST = 'contrast';
    const VARIANT_PLAIN = 'plain';

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
                $string !== self::VARIANT_CTA
                && $string !== self::VARIANT_CTA_CONTRAST
                && $string !== self::VARIANT_CONTRAST
                && $string !== self::VARIANT_PLAIN
            ) {
                throw ButtonVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function cta(): self
    {
        return self::from(self::VARIANT_CTA);
    }

    public static function ctaContrast(): self
    {
        return self::from(self::VARIANT_CTA_CONTRAST);
    }

    public static function contrast(): self
    {
        return self::from(self::VARIANT_CONTRAST);
    }

    public static function plain(): self
    {
        return self::from(self::VARIANT_PLAIN);
    }

    public function getIsCta(): bool
    {
        return $this->value === self::VARIANT_CTA;
    }

    public function getIsCtaContrast(): bool
    {
        return $this->value === self::VARIANT_CTA_CONTRAST;
    }

    public function getIsContrast(): bool
    {
        return $this->value === self::VARIANT_CONTRAST;
    }

    public function getIsPlain(): bool
    {
        return $this->value === self::VARIANT_PLAIN;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_CTA),
            self::from(self::VARIANT_CTA_CONTRAST),
            self::from(self::VARIANT_CONTRAST),
            self::from(self::VARIANT_PLAIN),
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
