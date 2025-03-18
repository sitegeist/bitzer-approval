<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;

#[Flow\Proxy(false)]
final class IsInsideHiddenParent
{
    public static function isSatisfiedByNode(
        ContentContext $targetContext,
        TraversableNodeInterface $site,
        TraversableNodeInterface $variant,
    ): bool {
        // check if site in target context is hidden
        if (!$targetContext->getNodeByIdentifier((string)$site->getNodeAggregateIdentifier())) {
            return true;
        }

        $parentNode = null;
        try {
            $parentNode = $variant->findParentNode();
        } catch (NodeException $exception) {
        }
        return !$parentNode;
    }
}
