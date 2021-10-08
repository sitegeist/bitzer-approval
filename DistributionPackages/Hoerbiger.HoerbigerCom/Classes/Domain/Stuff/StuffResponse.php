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

    public function __construct(int $numberOfPages, Things $results, int $totalNumberOfResults)
    {
        $this->numberOfPages = $numberOfPages;
        $this->results = $results;
        $this->totalNumberOfResults = $totalNumberOfResults;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'numberOfPages' => $this->numberOfPages,
            'results' => $this->results,
            'totalNumberOfResults' => $this->totalNumberOfResults
        ];
    }
}
