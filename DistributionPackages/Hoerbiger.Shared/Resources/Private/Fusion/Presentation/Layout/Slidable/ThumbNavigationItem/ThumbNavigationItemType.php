<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class ThumbNavigationItemType implements PseudoEnumInterface
{
    const TYPE_DEFAULT = 'default';
    const TYPE_PLAINTEXT = 'plainText';

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
            if (
                $string !== self::TYPE_DEFAULT
                && $string !== self::TYPE_PLAINTEXT
            ) {
                throw ThumbNavigationItemTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function default(): self
    {
        return self::from(self::TYPE_DEFAULT);
    }

    public function getIsDefault(): bool
    {
        return $this->value === self::TYPE_DEFAULT;
    }

    public static function plainText(): self
    {
        return self::from(self::TYPE_PLAINTEXT);
    }

    public function getIsPlainText(): bool
    {
        return $this->value === self::TYPE_PLAINTEXT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TYPE_DEFAULT),
            self::from(self::TYPE_PLAINTEXT),
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
