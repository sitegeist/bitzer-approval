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
    const VARIANT_LABEL = 'label';
    const VARIANT_CONTAINER = 'container';

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
            if ($string !== self::VARIANT_LABEL
                && $string !== self::VARIANT_CONTAINER) {
                throw LinkVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function label(): self
    {
        return self::from(self::VARIANT_LABEL);
    }

    public static function container(): self
    {
        return self::from(self::VARIANT_CONTAINER);
    }

    public function getIsLabel(): bool
    {
        return $this->value === self::VARIANT_LABEL;
    }

    public function getIsContainer(): bool
    {
        return $this->value === self::VARIANT_CONTAINER;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_LABEL),
            self::from(self::VARIANT_CONTAINER)
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
