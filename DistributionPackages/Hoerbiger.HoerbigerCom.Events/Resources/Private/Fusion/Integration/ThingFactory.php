<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Integration;

use Hoerbiger\HoerbigerCom\Domain\Stuff\Thing;
use Hoerbiger\HoerbigerCom\Domain\Stuff\Things;
use Hoerbiger\HoerbigerCom\Events\Presentation\Block\EventCard\EventCardFactory;
use Hoerbiger\Shared\Infrastructure\FusionRenderer;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class ThingFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly EventCardFactory $eventCardFactory
    ) {
    }
    /**
     * @param TraversableNodes<int, Node> $traversableNodes
     */
    public function forNodes(TraversableNodes $traversableNodes): Things
    {
        /** @var array<int,Node> $nodes */
        $nodes = $traversableNodes->toArray();

        return new Things(array_map(
            fn (Node $node): Thing => $this->createThing($node),
            $nodes
        ));
    }

    public function createThing(Node $node): Thing
    {
        $isExternalEvent = $node->getNodeType()->isOfType('Hoerbiger.HoerbigerCom.Events:Document.ExternalEvent');
        $targetProperty = $node->getProperty('target');

        $eventCard = $this->eventCardFactory->forEvent(
            $node,
            $isExternalEvent,
            (bool)$targetProperty || !$isExternalEvent
        );

        $toBeRenderedAsCard = $isExternalEvent && !$targetProperty;

        $fusionRenderer = new FusionRenderer();

        return new Thing(
            (string)$node->getNodeAggregateIdentifier(),
            $fusionRenderer->render(
                '/<Hoerbiger.HoerbigerCom.Events:Block.EventCard>/renderer',
                [
                    'presentationObject' => $eventCard
                ]
            )
        );
    }
}
