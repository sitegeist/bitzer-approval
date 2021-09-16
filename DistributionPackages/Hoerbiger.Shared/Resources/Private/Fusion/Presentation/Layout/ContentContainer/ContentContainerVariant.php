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
    const VARIANT_BLANK = 'blank';

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
                && $string !== self::VARIANT_BLANK) {
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

    public static function blank(): self
    {
        return self::from(self::VARIANT_BLANK);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsBlank(): bool
    {
        return $this->value === self::VARIANT_BLANK;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_BLANK)
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
