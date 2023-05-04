<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class IconSize implements PseudoEnumInterface
{
    const SIZE_SIZE_0_5 = 'size_0_5';
    const SIZE_SIZE_1 = 'size_1';
    const SIZE_SIZE_1_5 = 'size_1_5';
    const SIZE_SIZE_2 = 'size_2';
    const SIZE_SIZE_2_5 = 'size_2_5';
    const SIZE_SIZE_3 = 'size_3';
    const SIZE_SIZE_4 = 'size_4';
    const SIZE_PARENT = 'size_parent';

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
            if ($string !== self::SIZE_SIZE_0_5
                &&$string !== self::SIZE_SIZE_1
                && $string !== self::SIZE_SIZE_1_5
                && $string !== self::SIZE_SIZE_2
                && $string !== self::SIZE_SIZE_2_5
                && $string !== self::SIZE_SIZE_3
                && $string !== self::SIZE_SIZE_4
                && $string !== self::SIZE_PARENT) {
                throw IconSizeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function size_0_5(): self
    {
        return self::from(self::SIZE_SIZE_0_5);
    }

    public static function size_1(): self
    {
        return self::from(self::SIZE_SIZE_1);
    }

    public static function size_1_5(): self
    {
        return self::from(self::SIZE_SIZE_1_5);
    }

    public static function size_2(): self
    {
        return self::from(self::SIZE_SIZE_2);
    }

    public static function size_2_5(): self
    {
        return self::from(self::SIZE_SIZE_2_5);
    }

    public static function size_3(): self
    {
        return self::from(self::SIZE_SIZE_3);
    }

    public static function size_4(): self
    {
        return self::from(self::SIZE_SIZE_4);
    }

    public static function sizeParent(): self
    {
        return self::from(self::SIZE_PARENT);
    }

    public function getIsSize_0_5(): bool
    {
        return $this->value === self::SIZE_SIZE_0_5;
    }

    public function getIsSize_1(): bool
    {
        return $this->value === self::SIZE_SIZE_1;
    }

    public function getIsSize_1_5(): bool
    {
        return $this->value === self::SIZE_SIZE_1_5;
    }

    public function getIsSize_2(): bool
    {
        return $this->value === self::SIZE_SIZE_2;
    }

    public function getIsSize_2_5(): bool
    {
        return $this->value === self::SIZE_SIZE_2_5;
    }

    public function getIsSize_3(): bool
    {
        return $this->value === self::SIZE_SIZE_3;
    }

    public function getIsSize_4(): bool
    {
        return $this->value === self::SIZE_SIZE_4;
    }

    public function getIsSizeParent(): bool
    {
        return $this->value === self::SIZE_PARENT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::SIZE_SIZE_0_5),
            self::from(self::SIZE_SIZE_1),
            self::from(self::SIZE_SIZE_1_5),
            self::from(self::SIZE_SIZE_2),
            self::from(self::SIZE_SIZE_2_5),
            self::from(self::SIZE_SIZE_3),
            self::from(self::SIZE_SIZE_4),
            self::from(self::SIZE_PARENT)
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
