<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class SearchPage implements \JsonSerializable
{
    public function __construct(
        public int $value
    ) {
    }

    public static function fromInt(int $int): self
    {
        if ($int < 1) {
            throw SearchPageIsInvalid::becauseItMustBeGreaterZero($int);
        }

        return new self($int);
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
