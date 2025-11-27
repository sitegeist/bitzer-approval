<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class StuffResponse implements \JsonSerializable
{
    public function __construct(
        public int $numberOfPages,
        public Things $results,
        public int $totalNumberOfResults,
        public TypeOptions $typeOptions
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'numberOfPages' => $this->numberOfPages,
            'results' => $this->results,
            'totalNumberOfResults' => $this->totalNumberOfResults,
            'typeOptions' => $this->typeOptions
        ];
    }
}
