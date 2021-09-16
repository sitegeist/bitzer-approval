<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }
}
