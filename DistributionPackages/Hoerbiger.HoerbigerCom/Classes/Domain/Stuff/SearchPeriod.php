<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class SearchPeriod implements \JsonSerializable
{
    const VALUE_P1W = 'P1W';
    const VALUE_P1M = 'P1M';
    const VALUE_P1Y = 'P1Y';

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
            if ($string !== self::VALUE_P1W
                && $string !== self::VALUE_P1M
                && $string !== self::VALUE_P1Y) {
                throw SearchPeriodIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function lastWeek(): self
    {
        return self::from(self::VALUE_P1W);
    }

    public static function lastMonth(): self
    {
        return self::from(self::VALUE_P1M);
    }

    public static function lastYear(): self
    {
        return self::from(self::VALUE_P1Y);
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_P1W),
            self::from(self::VALUE_P1M),
            self::from(self::VALUE_P1Y)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getInterval(): \DateInterval
    {
        return new \DateInterval($this->value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
