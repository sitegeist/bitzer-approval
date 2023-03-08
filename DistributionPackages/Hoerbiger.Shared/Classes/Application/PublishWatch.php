<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Application;

use Hoerbiger\Shared\Domain\Anchor;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\Shared\Domain\Sailor;

class PublishWatch
{
    public function watchForRedundantContentElementIdentifier(NodeInterface $node): void
    {
        if ($node->getNodeType()->isOfType('Neos.Neos:Content') && $node->getProperty('anchorID')) {
            $availableAnchors = array_map(
                fn(Anchor $anchor): string
                => $anchor->getIdentifier(),
                (new Sailor())->findAvailableNeighbouringAnchors($node)
            );

            if (count($availableAnchors) !== count(array_unique($availableAnchors))) {
                throw new \Exception("Es gibt bereits Content-Elemente mit gleicher Anchor-Bezeichnung", 1678289101);
            }
        }
    }
}
