<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\ContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class ContentContainerVariant implements PseudoEnumInterface
{
    const VARIANT_REGULAR = 'regular';
    const VARIANT_REGULARNEUTRAL = 'regularNeutral';
    const VARIANT_SNUG = 'snug';
    const VARIANT_TIGHT = 'tight';
    const VARIANT_REGULARSM = 'regularSm';
    const VARIANT_REGULARX = 'regularX';
    const VARIANT_BLANK = 'blank';
    const VARIANT_BLANKY = 'blankY';
    const VARIANT_STAGEAFTERBREADCRUMB = 'stageAfterBreadcrumb';
    const VARIANT_REGULARSTAGEAFTERBREADCRUMB = 'regularStageAfterBreadcrumb';
    const VARIANT_REGULAR_WITHOUT_BOTTOM = 'regularWithoutBottom';

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
                && $string !== self::VARIANT_REGULARNEUTRAL
                && $string !== self::VARIANT_SNUG
                && $string !== self::VARIANT_TIGHT
                && $string !== self::VARIANT_REGULARSM
                && $string !== self::VARIANT_REGULARX
                && $string !== self::VARIANT_BLANK
                && $string !== self::VARIANT_BLANKY
                && $string !== self::VARIANT_REGULARSTAGEAFTERBREADCRUMB
                && $string !== self::VARIANT_STAGEAFTERBREADCRUMB
                && $string !== self::VARIANT_REGULAR_WITHOUT_BOTTOM) {
                throw ContentContainerVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function regular(): self
    {
        return self::from(self::VARIANT_REGULAR);
    }

    public static function regularNeutral(): self
    {
        return self::from(self::VARIANT_REGULARNEUTRAL);
    }

    public static function snug(): self
    {
        return self::from(self::VARIANT_SNUG);
    }

    public static function tight(): self
    {
        return self::from(self::VARIANT_TIGHT);
    }

    public static function regularSm(): self
    {
        return self::from(self::VARIANT_REGULARSM);
    }

    public static function regularX(): self
    {
        return self::from(self::VARIANT_REGULARX);
    }

    public static function blank(): self
    {
        return self::from(self::VARIANT_BLANK);
    }

    public static function blankY(): self
    {
        return self::from(self::VARIANT_BLANKY);
    }

    public static function stageAfterBreadcrumb(): self
    {
        return self::from(self::VARIANT_STAGEAFTERBREADCRUMB);
    }

    public static function regularStageAfterBreadcrumb(): self
    {
        return self::from(self::VARIANT_REGULARSTAGEAFTERBREADCRUMB);
    }

    public static function regularWithoutBottom(): self
    {
        return self::from(self::VARIANT_REGULAR_WITHOUT_BOTTOM);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsRegularNeutral(): bool
    {
        return $this->value === self::VARIANT_REGULARNEUTRAL;
    }

    public function getIsSnug(): bool
    {
        return $this->value === self::VARIANT_SNUG;
    }

    public function getIsTight(): bool
    {
        return $this->value === self::VARIANT_TIGHT;
    }

    public function getIsRegularSm(): bool
    {
        return $this->value === self::VARIANT_REGULARSM;
    }

    public function getIsRegularX(): bool
    {
        return $this->value === self::VARIANT_REGULARX;
    }

    public function getIsBlank(): bool
    {
        return $this->value === self::VARIANT_BLANK;
    }

    public function getIsBlankY(): bool
    {
        return $this->value === self::VARIANT_BLANKY;
    }

    public function getIsStageAfterBreadcrumb(): bool
    {
        return $this->value === self::VARIANT_STAGEAFTERBREADCRUMB;
    }

    public function getIsRegularStageAfterBreadcrumb(): bool
    {
        return $this->value === self::VARIANT_REGULARSTAGEAFTERBREADCRUMB;
    }

    public function getIsRegularWithoutBottom(): bool
    {
        return $this->value === self::VARIANT_REGULAR_WITHOUT_BOTTOM;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_REGULARNEUTRAL),
            self::from(self::VARIANT_SNUG),
            self::from(self::VARIANT_TIGHT),
            self::from(self::VARIANT_REGULARSM),
            self::from(self::VARIANT_REGULARX),
            self::from(self::VARIANT_BLANK),
            self::from(self::VARIANT_BLANKY),
            self::from(self::VARIANT_STAGEAFTERBREADCRUMB),
            self::from(self::VARIANT_REGULARSTAGEAFTERBREADCRUMB),
            self::from(self::VARIANT_REGULAR_WITHOUT_BOTTOM)
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
