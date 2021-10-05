<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class TextVariant implements PseudoEnumInterface
{
    const VARIANT_LIGHTCONTRAST = 'lightContrast';
    const VARIANT_BRANDCONTRAST = 'brandContrast';
    const VARIANT_NEUTRALCONTRAST = 'neutralContrast';
    const VARIANT_TRANSPARENTCONTRAST = 'transparentContrast';

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
            if ($string !== self::VARIANT_LIGHTCONTRAST
                && $string !== self::VARIANT_BRANDCONTRAST
                && $string !== self::VARIANT_NEUTRALCONTRAST
                && $string !== self::VARIANT_TRANSPARENTCONTRAST) {
                throw TextVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function lightContrast(): self
    {
        return self::from(self::VARIANT_LIGHTCONTRAST);
    }

    public static function brandContrast(): self
    {
        return self::from(self::VARIANT_BRANDCONTRAST);
    }

    public static function neutralContrast(): self
    {
        return self::from(self::VARIANT_NEUTRALCONTRAST);
    }

    public static function transparentContrast(): self
    {
        return self::from(self::VARIANT_TRANSPARENTCONTRAST);
    }

    public function getIsLightContrast(): bool
    {
        return $this->value === self::VARIANT_LIGHTCONTRAST;
    }

    public function getIsBrandContrast(): bool
    {
        return $this->value === self::VARIANT_BRANDCONTRAST;
    }

    public function getIsNeutralContrast(): bool
    {
        return $this->value === self::VARIANT_NEUTRALCONTRAST;
    }

    public function getIsTransparentContrast(): bool
    {
        return $this->value === self::VARIANT_TRANSPARENTCONTRAST;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_LIGHTCONTRAST),
            self::from(self::VARIANT_BRANDCONTRAST),
            self::from(self::VARIANT_NEUTRALCONTRAST),
            self::from(self::VARIANT_TRANSPARENTCONTRAST)
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
