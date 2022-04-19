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
    const TYPE_APPLICATION_WORLD = 'ApplicationWorld';
    const TYPE_PEOPLE_AND_CULTURE = 'PeopleAndCulture';
    const TYPE_INDUSTRY_SOLUTION = 'IndustrySolution';
    const TYPE_SUCCESS_STORY = 'SuccessStory';
    const TYPE_PRESS_RELEASE = 'PressRelease';
    const TYPE_PRIVACY = 'Privacy';

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
                && $string !== self::TYPE_DOCUMENT
                && $string !== self::TYPE_APPLICATION_WORLD
                && $string !== self::TYPE_PEOPLE_AND_CULTURE
                && $string !== self::TYPE_INDUSTRY_SOLUTION
                && $string !== self::TYPE_SUCCESS_STORY
                && $string !== self::TYPE_PRESS_RELEASE
                && $string !== self::TYPE_PRIVACY) {
                throw SearchTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
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
