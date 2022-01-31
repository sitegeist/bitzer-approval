<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.Catalog:Collection.ProductList':
                return $this->forProductListNode($contentNode);
            case 'Hoerbiger.HoerbigerCom.Catalog:Content.GroupedProducts':
                return (new CardFactory())->forGroupedProductsNode($contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    public function forProductListNode(TraversableNodeInterface $productListNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::threeColumnsGap(),
                Collection::fromNodes(
                    $productListNode->findChildNodes(),
                    function (TraversableNodeInterface $groupedProduct) {
                        return Content::fromNode(
                            $groupedProduct,
                            'Hoerbiger.HoerbigerCom.Catalog:ContentSlot'
                        );
                    }
                )
            )
        );
    }
}
