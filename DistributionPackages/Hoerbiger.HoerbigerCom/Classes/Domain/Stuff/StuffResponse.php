<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class StuffResponse implements \JsonSerializable
{
    private int $numberOfPages;

    private Things $results;

    private int $totalNumberOfResults;

    private TypeOptions $typeOptions;

    public function __construct(
        int $numberOfPages,
        Things $results,
        int $totalNumberOfResults,
        TypeOptions $typeOptions
    ) {
        $this->numberOfPages = $numberOfPages;
        $this->results = $results;
        $this->totalNumberOfResults = $totalNumberOfResults;
        $this->typeOptions = $typeOptions;
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
