<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class SearchPage implements \JsonSerializable
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromInt(int $int): self
    {
        if ($int < 1) {
            throw SearchPageIsInvalid::becauseItMustBeGreaterZero($int);
        }

        return new self($int);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
