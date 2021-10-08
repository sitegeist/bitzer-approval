<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class SortingProperty implements \JsonSerializable
{
    const PROPERTY_SCORE = '_score';
    const PROPERTY_DATE_MODIFIED = 'dateModified';

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
            if ($string !== self::PROPERTY_SCORE
                && $string !== self::PROPERTY_DATE_MODIFIED) {
                throw SortingPropertyIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function score(): self
    {
        return self::from(self::PROPERTY_SCORE);
    }

    public static function dateModified(): self
    {
        return self::from(self::PROPERTY_DATE_MODIFIED);
    }

    public function getIsScore(): bool
    {
        return $this->value === self::PROPERTY_SCORE;
    }

    public function getIsDateModified(): bool
    {
        return $this->value === self::PROPERTY_DATE_MODIFIED;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::PROPERTY_SCORE),
            self::from(self::PROPERTY_DATE_MODIFIED)
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

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
