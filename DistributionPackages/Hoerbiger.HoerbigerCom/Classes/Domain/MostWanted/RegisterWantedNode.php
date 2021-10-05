<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MostWanted;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;

/**
 * The command to register a node as wanted
 *
 * @Flow\Proxy(false)
 */
final class RegisterWantedNode
{
    private string $language;

    private NodeAggregateIdentifier $nodeAggregateIdentifier;

    private function __construct(string $language, NodeAggregateIdentifier $nodeAggregateIdentifier)
    {
        $this->language = $language;
        $this->nodeAggregateIdentifier = $nodeAggregateIdentifier;
    }

    /**
     * @param array<string,string> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['language'],
            NodeAggregateIdentifier::fromString($array['nodeAggregateIdentifier'])
        );
    }

    public static function fromJsonString(string $jsonString): self
    {
        return self::fromArray(\json_decode($jsonString, true));
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getNodeAggregateIdentifier(): NodeAggregateIdentifier
    {
        return $this->nodeAggregateIdentifier;
    }
}
