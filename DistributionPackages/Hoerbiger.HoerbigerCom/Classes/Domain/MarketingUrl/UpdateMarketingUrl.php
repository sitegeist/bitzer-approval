<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MarketingUrl;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;

/**
 * The command to update a marketing URL
 *
 * @Flow\Proxy(false)
 */
final class UpdateMarketingUrl
{
    private string $path;

    private NodeAggregateIdentifier $nodeAggregateIdentifier;

    public function __construct(string $path, NodeAggregateIdentifier $nodeAggregateIdentifier)
    {
        $this->path = $path;
        $this->nodeAggregateIdentifier = $nodeAggregateIdentifier;
    }

    /**
     * @param array<string,string> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['path'],
            NodeAggregateIdentifier::fromString($array['nodeAggregateIdentifier'])
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getNodeAggregateIdentifier(): NodeAggregateIdentifier
    {
        return $this->nodeAggregateIdentifier;
    }
}
