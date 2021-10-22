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
    const VARIANT_REGULARSM = 'regularSm';
    const VARIANT_REGULARX = 'regularX';
    const VARIANT_BLANK = 'blank';
    const VARIANT_STAGEAFTERBREADCRUMB = 'stageAfterBreadcrumb';

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
                && $string !== self::VARIANT_REGULARSM
                && $string !== self::VARIANT_REGULARX
                && $string !== self::VARIANT_BLANK
                && $string !== self::VARIANT_STAGEAFTERBREADCRUMB) {
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

    public static function stageAfterBreadcrumb(): self
    {
        return self::from(self::VARIANT_STAGEAFTERBREADCRUMB);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
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

    public function getIsStageAfterBreadcrumb(): bool
    {
        return $this->value === self::VARIANT_STAGEAFTERBREADCRUMB;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_REGULARSM),
            self::from(self::VARIANT_REGULARX),
            self::from(self::VARIANT_BLANK),
            self::from(self::VARIANT_STAGEAFTERBREADCRUMB)
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
