<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class LinkVariant implements PseudoEnumInterface
{
    const VARIANT_CONTAINER = 'container';
    const VARIANT_LABEL = 'label';
    const VARIANT_TOUCHICON = 'touchIcon';
    const VARIANT_NOSTYLES = 'noStyles';
    const VARIANT_SITEFOOTER = 'siteFooter';
    const VARIANT_PLAINLABEL = 'plainLabel';

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
            if ($string !== self::VARIANT_CONTAINER
                && $string !== self::VARIANT_LABEL
                && $string !== self::VARIANT_TOUCHICON
                && $string !== self::VARIANT_NOSTYLES
                && $string !== self::VARIANT_SITEFOOTER
                && $string !== self::VARIANT_PLAINLABEL) {
                throw LinkVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function container(): self
    {
        return self::from(self::VARIANT_CONTAINER);
    }

    public static function label(): self
    {
        return self::from(self::VARIANT_LABEL);
    }

    public static function touchIcon(): self
    {
        return self::from(self::VARIANT_TOUCHICON);
    }

    public static function noStyles(): self
    {
        return self::from(self::VARIANT_NOSTYLES);
    }

    public static function siteFooter(): self
    {
        return self::from(self::VARIANT_SITEFOOTER);
    }

    public static function plainLabel(): self
    {
        return self::from(self::VARIANT_PLAINLABEL);
    }

    public function getIsContainer(): bool
    {
        return $this->value === self::VARIANT_CONTAINER;
    }

    public function getIsLabel(): bool
    {
        return $this->value === self::VARIANT_LABEL;
    }

    public function getIsTouchIcon(): bool
    {
        return $this->value === self::VARIANT_TOUCHICON;
    }

    public function getIsNoStyles(): bool
    {
        return $this->value === self::VARIANT_NOSTYLES;
    }

    public function getIsSiteFooter(): bool
    {
        return $this->value === self::VARIANT_SITEFOOTER;
    }

    public function getIsPlainLabel(): bool
    {
        return $this->value === self::VARIANT_PLAINLABEL;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_CONTAINER),
            self::from(self::VARIANT_LABEL),
            self::from(self::VARIANT_TOUCHICON),
            self::from(self::VARIANT_NOSTYLES),
            self::from(self::VARIANT_SITEFOOTER),
            self::from(self::VARIANT_PLAINLABEL)
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
