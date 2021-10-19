<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }
}
