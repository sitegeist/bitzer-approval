<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Presentation\Application\PaybackCalculatorEVCP\PaybackCalculatorEVCPFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly PaybackCalculatorEVCPFactory $paybackCalculatorECVPFactory,
    ) {
    }
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.Catalog:Collection.ProductList':
                return $this->forProductListNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom.Catalog:Content.GroupedProducts':
                return (new CardFactory())->forGroupedProductsNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom.Catalog:Content.GroupedLinks':
                return (new CardFactory())->forGroupedLinksNode($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom.Catalog:Content.PaybackCalculatorEVCP':
                return $this->forPaybackCalculatorEVCPNode($contentNode, $subgraph, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    public function forProductListNode(TraversableNodeInterface $productListNode, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $productListNode->getProperty('headline')
                        ? new Headline(
                            HeadlineSize::SIZE_MEDIUM_ITALIC,
                            HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                            HeadlineType::TYPE_H2,
                            Editable::fromNodeProperty($productListNode, 'headline')
                        ) : null,
                    new Grid(
                        GridVariant::VARIANT_THREECOLUMNSGAP,
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
                ]))
            )
        );
    }

    public function forPaybackCalculatorEVCPNode(TraversableNodeInterface $paybackCalculatorEVCPNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            $this->paybackCalculatorECVPFactory->create($paybackCalculatorEVCPNode, $subgraph, $inBackend)
        );
    }
}
