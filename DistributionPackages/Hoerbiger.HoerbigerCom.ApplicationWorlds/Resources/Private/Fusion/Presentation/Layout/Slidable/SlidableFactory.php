<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class SlidableFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    public function __construct(StageFactory $stageFactory)
    {
        $this->stageFactory = $stageFactory;
    }

    public function forApplicationWorlds(TraversableNodeInterface $node, NodeTypeName $targetNodeTypeName, bool $inBackend): Slidable
    {
        $targetNodes = $node->findChildNodes(
            new NodeTypeConstraints(
                false,
                [$targetNodeTypeName]
            ));

        $linkFactory = new LinkFactory();

        return new Slidable(
            Collection::fromNodes($targetNodes, function($targetNode) use ($inBackend, $linkFactory) {
                $link = $linkFactory->forLinkToDocument($targetNode);

                /** @var SlotInterface $stage */
                $stage = $this->stageFactory->forApplicationWorldSlideStage($targetNode, $inBackend);

                return $link->asContainer()->withContent($stage);
            }),
            Collection::fromNodes($targetNodes, function($targetNode) {
                $thumbLabel = Value::fromString('');

                if ($targetNode->getProperty('preview__headline')) {
                    $thumbLabel = Value::fromString($targetNode->getProperty('preview__headline'));
                } elseif ($targetNode->getProperty('headline')) {
                    $thumbLabel = Value::fromString($targetNode->getProperty('headline'));
                }


                return $thumbLabel;
            })
        );
    }
}
