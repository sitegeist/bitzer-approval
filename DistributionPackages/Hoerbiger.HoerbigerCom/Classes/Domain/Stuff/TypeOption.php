<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class TypeOption implements \JsonSerializable
{
    public function __construct(
        public string $identifier,
        public int $totalNumberOfResults
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'totalNumberOfResults' => $this->totalNumberOfResults
        ];
    }
}
