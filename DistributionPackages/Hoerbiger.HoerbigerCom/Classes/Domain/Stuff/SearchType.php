<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class SearchType implements \JsonSerializable
{
    const TYPE_PRODUCT = 'Product';
    const TYPE_WEBPAGE = 'WebPage';
    const TYPE_DOCUMENT = 'Document';

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
            if ($string !== self::TYPE_PRODUCT
                && $string !== self::TYPE_WEBPAGE
                && $string !== self::TYPE_DOCUMENT) {
                throw SearchTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function product(): self
    {
        return self::from(self::TYPE_PRODUCT);
    }

    public static function webPage(): self
    {
        return self::from(self::TYPE_WEBPAGE);
    }

    public static function document(): self
    {
        return self::from(self::TYPE_DOCUMENT);
    }

    public function getIsProduct(): bool
    {
        return $this->value === self::TYPE_PRODUCT;
    }

    public function getIsWebPage(): bool
    {
        return $this->value === self::TYPE_WEBPAGE;
    }

    public function getIsDocument(): bool
    {
        return $this->value === self::TYPE_DOCUMENT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TYPE_PRODUCT),
            self::from(self::TYPE_WEBPAGE),
            self::from(self::TYPE_DOCUMENT)
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
