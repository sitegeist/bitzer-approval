<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\ProductCard\ProductCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.Catalog:Collection.ProductList':
                return $this->forProductListNode($contentNode);
            case 'Hoerbiger.HoerbigerCom.Catalog:Content.GroupedProducts':
                return $this->forGroupedProductsNode($contentNode, $subgraph, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    public function forProductListNode(TraversableNodeInterface $productListNode): SlotInterface
    {
        /*\Neos\Flow\var_dump(Collection::fromNodes(
            $productListNode->findChildNodes(),
            function(TraversableNodeInterface $groupedProduct){
                return Content::fromNode(
                    $groupedProduct,
                    'Hoerbiger.HoerbigerCom.Catalog:ContentSlot'
                );
            }
        ));die;*/

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::threeColumnsGap(),
                Collection::fromNodes(
                    $productListNode->findChildNodes(),
                    function(TraversableNodeInterface $groupedProduct){
                        return Content::fromNode(
                            $groupedProduct,
                            'Hoerbiger.HoerbigerCom.Catalog:ContentSlot'
                        );
                    }
                )
            )
        );
    }

    public function forGroupedProductsNode(TraversableNodeInterface $groupedProductListNode,ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        $figure = (new FigureFactory())->forImageMixin($groupedProductListNode, $inBackend);
        if ($figure) {
            $figure = $figure->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center());
        }

        $products = Collection::fromNodes(
            TraversableNodes::fromArray($groupedProductListNode->getProperty('products') ?: []),
            function(TraversableNodeInterface $productDocument) {
                $link = (new LinkFactory())
                    ->forLinkToDocument($productDocument)
                    ->asContainer();

                return $link->withContent(
                    Collection::fromIterable([
                        Value::fromString($productDocument->getProperty('headline') ?: ''),
                        new Icon('shared', 'arrowRight')
                    ], function(SlotInterface $slot) {
                        return $slot;
                    })
                );
            }
        );

        return new ProductCard(
            $figure,
            Editable::fromNodeProperty($groupedProductListNode, 'name'),
            null,
            null,
            $products
        );
    }
}
