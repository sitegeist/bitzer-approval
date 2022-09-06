<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class TagVariant implements PseudoEnumInterface
{
    const VARIANT_BRANDCOLOR = 'brandColor';
    const VARIANT_BRANDCOLOR_CONTRAST = 'brandColorContrast';
    const VARIANT_TRANSPARENT = 'transparent';

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
            if ($string !== self::VARIANT_BRANDCOLOR && $string !== self::VARIANT_BRANDCOLOR_CONTRAST && $string !== self::VARIANT_TRANSPARENT) {
                throw TagVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function brandColor(): self
    {
        return self::from(self::VARIANT_BRANDCOLOR);
    }

    public static function brandColorContrast(): self
    {
        return self::from(self::VARIANT_BRANDCOLOR_CONTRAST);
    }

    public static function transparent(): self
    {
        return self::from(self::VARIANT_TRANSPARENT);
    }

    public function getIsBrandColor(): bool
    {
        return $this->value === self::VARIANT_BRANDCOLOR;
    }

    public function getIsBrandColorContrast(): bool
    {
        return $this->value === self::VARIANT_BRANDCOLOR_CONTRAST;
    }

    public function getIsTransparent(): bool
    {
        return $this->value === self::VARIANT_TRANSPARENT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_BRANDCOLOR),
            self::from(self::VARIANT_BRANDCOLOR_CONTRAST),
            self::from(self::VARIANT_TRANSPARENT)
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
