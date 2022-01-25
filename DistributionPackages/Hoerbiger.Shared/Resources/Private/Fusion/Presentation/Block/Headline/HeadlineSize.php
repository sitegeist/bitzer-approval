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
final class HeadlineSize implements PseudoEnumInterface
{
    const SIZE_LARGE = 'large';
    const SIZE_MEDIUM = 'medium';

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
            if ($string !== self::SIZE_LARGE
                && $string !== self::SIZE_MEDIUM) {
                throw HeadlineSizeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function large(): self
    {
        return self::from(self::SIZE_LARGE);
    }

    public static function medium(): self
    {
        return self::from(self::SIZE_MEDIUM);
    }

    public function getIsLarge(): bool
    {
        return $this->value === self::SIZE_LARGE;
    }

    public function getIsMedium(): bool
    {
        return $this->value === self::SIZE_MEDIUM;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::SIZE_LARGE),
            self::from(self::SIZE_MEDIUM)
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
