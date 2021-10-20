<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class SlidableFactory extends AbstractComponentPresentationObjectFactory
{
    public function forApplicationWorlds(TraversableNodeInterface $node, NodeTypeName $targetNodeTypeName, ContentContext $subgraph, bool $inBackend): Slidable
    {
        $targetNodes = $node->findChildNodes(
            new NodeTypeConstraints(
                false,
                [$targetNodeTypeName]
            ));

        $stageFactory = new StageFactory();
        $linkFactory = new LinkFactory();

        return new Slidable(
            Collection::fromNodes($targetNodes, function($targetNode) use ($inBackend, $subgraph, $stageFactory, $linkFactory) {
                $link = $linkFactory->forLinkToDocument($targetNode, $subgraph);

                /** @var SlotInterface $stage */
                $stage = $stageFactory->forApplicationWorldSlideStage($targetNode, $inBackend);

                return $link->asContainer()->withContent($stage);
            }),
            Collection::fromNodes($targetNodes, function($targetNode) use ($inBackend) {
                $figure = (new FigureFactory())->forImageProperty($targetNode, $inBackend, true, 'slider__preview__image');
                if ($figure) {
                    $figure = $figure->withObjectFitAndPosition(FigureObjectFit::contain(), FigureObjectPosition::center());
                }

                return new ThumbNavigationItem(
                    $figure,
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: ''
                );
            })
        );
    }
}
