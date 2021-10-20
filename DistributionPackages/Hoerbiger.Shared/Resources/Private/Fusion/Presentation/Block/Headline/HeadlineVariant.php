<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class HeadlineVariant implements PseudoEnumInterface
{
    const VARIANT_REGULAR = 'regular';
    const VARIANT_IMPORTANT = 'important';
    const VARIANT_DECORATIVE = 'decorative';
    const VARIANT_NOBRAND = 'noBrand';

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
                && $string !== self::VARIANT_IMPORTANT
                && $string !== self::VARIANT_DECORATIVE
                && $string !== self::VARIANT_NOBRAND) {
                throw HeadlineVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function regular(): self
    {
        return self::from(self::VARIANT_REGULAR);
    }

    public static function important(): self
    {
        return self::from(self::VARIANT_IMPORTANT);
    }

    public static function decorative(): self
    {
        return self::from(self::VARIANT_DECORATIVE);
    }

    public static function noBrand(): self
    {
        return self::from(self::VARIANT_NOBRAND);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsImportant(): bool
    {
        return $this->value === self::VARIANT_IMPORTANT;
    }

    public function getIsDecorative(): bool
    {
        return $this->value === self::VARIANT_DECORATIVE;
    }

    public function getIsNoBrand(): bool
    {
        return $this->value === self::VARIANT_NOBRAND;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_IMPORTANT),
            self::from(self::VARIANT_DECORATIVE),
            self::from(self::VARIANT_NOBRAND)
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
