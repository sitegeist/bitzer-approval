<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class TypeOption implements \JsonSerializable
{
    private string $identifier;

    private int $totalNumberOfResults;

    public function __construct(string $identifier, int $totalNumberOfResults)
    {
        $this->identifier = $identifier;
        $this->totalNumberOfResults = $totalNumberOfResults;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getTotalNumberOfResults(): int
    {
        return $this->totalNumberOfResults;
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
