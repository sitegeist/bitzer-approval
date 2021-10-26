<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MostWanted;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if a node was unsuccessfully attempted to be registered as wanted
 *
 * @Flow\Proxy(false)
 */
final class NodeCannotBeRegisteredAsWanted extends \DomainException
{
    public static function becauseItDoesNotCoverLanguage(
        string $language,
        NodeAggregateIdentifier $nodeAggregateIdentifier
    ): self {
        return new self('Node ' . $nodeAggregateIdentifier
            . ' cannot be marked as wanted since it does not exist in language "' . $language . '"', 1633439931);
    }
}
