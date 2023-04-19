<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class PressReleaseCardVariant implements PseudoEnumInterface
{
    const VARIANT_META = 'meta';
    const VARIANT_NEWS = 'news';
    const VARIANT_TOP = 'top';
    const VARIANT_TOP_NEWS = 'topNews';
    const VARIANT_HANNS = 'hanns';
    const VARIANT_YEARBOOK = 'yearbook';

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
            if ($string !== self::VARIANT_META
                && $string !== self::VARIANT_NEWS
                && $string !== self::VARIANT_TOP
                && $string !== self::VARIANT_TOP_NEWS
                && $string !== self::VARIANT_HANNS
                && $string !== self::VARIANT_YEARBOOK) {
                throw PressReleaseCardVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function meta(): self
    {
        return self::from(self::VARIANT_META);
    }

    public static function news(): self
    {
        return self::from(self::VARIANT_NEWS);
    }

    public static function top(): self
    {
        return self::from(self::VARIANT_TOP);
    }

    public static function topNews(): self
    {
        return self::from(self::VARIANT_TOP_NEWS);
    }

    public static function hanns(): self
    {
        return self::from(self::VARIANT_HANNS);
    }

    public static function yearbook(): self
    {
        return self::from(self::VARIANT_YEARBOOK);
    }

    public function getIsMeta(): bool
    {
        return $this->value === self::VARIANT_META;
    }

    public function getIsNews(): bool
    {
        return $this->value === self::VARIANT_NEWS;
    }

    public function getIsTop(): bool
    {
        return $this->value === self::VARIANT_TOP;
    }
    public function getIsTopNews(): bool
    {
        return $this->value === self::VARIANT_TOP_NEWS;
    }

    public function getIsHanns(): bool
    {
        return $this->value === self::VARIANT_HANNS;
    }

    public function getIsYearbook(): bool
    {
        return $this->value === self::VARIANT_YEARBOOK;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_META),
            self::from(self::VARIANT_NEWS),
            self::from(self::VARIANT_TOP),
            self::from(self::VARIANT_TOP_NEWS),
            self::from(self::VARIANT_HANNS),
            self::from(self::VARIANT_YEARBOOK)
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
