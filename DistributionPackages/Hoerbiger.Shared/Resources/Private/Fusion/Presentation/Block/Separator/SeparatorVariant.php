<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class SeparatorVariant implements PseudoEnumInterface
{
    const VARIANT_REGULAR = 'regular';
    const VARIANT_BOLD = 'bold';
    const VARIANT_FULL_WIDTH_WITH_CONTENT = 'fullWidthWithContent';

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
                && $string !== self::VARIANT_BOLD
                && $string !== self::VARIANT_FULL_WIDTH_WITH_CONTENT
            ) {
                throw SeparatorVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function regular(): self
    {
        return self::from(self::VARIANT_REGULAR);
    }

    public static function bold(): self
    {
        return self::from(self::VARIANT_BOLD);
    }

    public static function fullWidthWithContent(): self
    {
        return self::from(self::VARIANT_FULL_WIDTH_WITH_CONTENT);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsBold(): bool
    {
        return $this->value === self::VARIANT_BOLD;
    }

    public function getIsFullWidthWithContent(): bool
    {
        return $this->value === self::VARIANT_FULL_WIDTH_WITH_CONTENT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_BOLD),
            self::from(self::VARIANT_FULL_WIDTH_WITH_CONTENT)
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
