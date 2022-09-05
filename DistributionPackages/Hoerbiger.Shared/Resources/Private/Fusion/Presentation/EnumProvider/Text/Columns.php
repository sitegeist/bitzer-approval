<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class Columns implements PseudoEnumInterface
{
    const VALUE_MAX_ONE = 'maxOne';
    const VALUE_MAX_TWO = 'maxTwo';
    const VALUE_MAX_ONE_LEFT = 'maxOneLeft';

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
            if ($string !== self::VALUE_MAX_ONE
                && $string !== self::VALUE_MAX_TWO
                && $string !== self::VALUE_MAX_ONE_LEFT) {
                throw ColumnsIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function maxOne(): self
    {
        return self::from(self::VALUE_MAX_ONE);
    }

    public static function maxTwo(): self
    {
        return self::from(self::VALUE_MAX_TWO);
    }

    public static function maxOneLeft(): self
    {
        return self::from(self::VALUE_MAX_ONE_LEFT);
    }

    public function getIsMaxOne(): bool
    {
        return $this->value === self::VALUE_MAX_ONE;
    }

    public function getIsMaxTwo(): bool
    {
        return $this->value === self::VALUE_MAX_TWO;
    }

    public function getIsMaxOneLeft(): bool
    {
        return $this->value === self::VALUE_MAX_ONE_LEFT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_MAX_ONE),
            self::from(self::VALUE_MAX_TWO),
            self::from(self::VALUE_MAX_ONE_LEFT)
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
