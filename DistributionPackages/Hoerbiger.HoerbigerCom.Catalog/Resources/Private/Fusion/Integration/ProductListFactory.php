<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */


use Hoerbiger\HoerbigerCom\Catalog\Domain\Catalog;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card\CardFactory;
use Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Page\PageFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ProductListFactory
{
    /**
     * @Flow\Inject
     * @var CsvPOTranslator
     */
    protected $csvPOTranslator;

    /**
     * @Flow\Inject
     * @var Catalog
     */
    protected $catalog;

    public function forIndustrySolution(
        TraversableNodeInterface $industrySolution,
        ContentContext $subGraph,
        bool $inBackend
    ): SlotInterface {
        $products = $this->catalog->findProductsForIndustrySolution($subGraph,$industrySolution);

        return $this->createProductGroupCards(
            $industrySolution->getLabel(),
            $products,
            $subGraph,
            $inBackend
        );
    }

    public function forApplicationWorld(
        TraversableNodeInterface $applicationWorld,
        ContentContext $subGraph,
        bool $inBackend
    ): SlotInterface {
        $products = $this->catalog->findProductsForApplicationWorld($subGraph,$applicationWorld);

        return $this->createProductGroupCards(
            $applicationWorld->getLabel(),
            $products,
            $subGraph,
            $inBackend
        );
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $products
     */
    private function createProductGroupCards(
        string $productListTitle,
        TraversableNodes $products,
        ContentContext $subGraph,
        bool $inBackend
    ): SlotInterface {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        $freestandingGroup = $this->catalog->findProductGroupByNodeName('freestanding', $subGraph);

        $cardFactory = new CardFactory();

        $productsByGroup = array_fill_keys($this->catalog->findProductGroupIdentifiers($subGraph),[]);

        foreach ($products as $product) {
            /** @var TraversableNodeInterface $product */
            /** @var TraversableNodeInterface $productGroup */
            $productGroup = $product->getProperty('productGroup') ?: $freestandingGroup;

            $productsByGroup[(string)$productGroup->getNodeAggregateIdentifier()][] = $product;
        }

        $productCards = [];
        foreach ($productsByGroup as $productGroupNodeIdentifier => $products) {
            /** @var string $productGroupNodeIdentifier */

            if(!empty($products)) {
                /** @var ?TraversableNodeInterface $productGroup */
                $productGroup = $subGraph->getNodeByIdentifier($productGroupNodeIdentifier);
                if ($productGroup) {
                    $productCards[] = $cardFactory->forProductGroup(
                        $productGroup,
                        $products,
                        $inBackend
                    );
                }
            }
        }

        return new CacheSegment(
            new ContentContainer(
                ContentContainerVariant::regular(),
                new Stack(
                    StackVariant::sm(),
                    Collection::fromIterable([
                        new Headline(
                            HeadlineSize::large(),
                            HeadlineColor::brand(),
                            HeadlineType::h2(),
                            Value::fromString(
                                $this->csvPOTranslator->getLabel(PageFactory::class,'products')
                                . ' '
                                . $productListTitle
                            )
                        ),
                        new Grid(
                            GridVariant::threeColumnsGap(),
                            Collection::fromIterable(
                                $productCards,
                                $identity
                            )
                        )
                    ], $identity)
                )
            ),
            'Hoerbiger.HoerbigerCom.Catalog:CacheSegment.ProductGroup'
        );
    }
}
