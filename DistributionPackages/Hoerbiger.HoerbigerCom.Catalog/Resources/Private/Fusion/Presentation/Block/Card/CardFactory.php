<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Domain\Catalog;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Card\ProductCard\ProductCard;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    /**
     * @Flow\Inject
     * @var Catalog
     */
    protected $catalog;

    /**
     * @Flow\Inject
     * @var ImageSourceFactory
     */
    protected $imageSourceFactory;

    /**
     * @Flow\Inject
     * @var FigureFactory
     */
    protected $figureFactory;

    /**
     * @return array<int,ProductCard>
     */
    public function forIndustrySolutionProducts(ContentContext $subgraph, TraversableNodeInterface $industrySolution): array
    {
        $cards = [];

        foreach ($this->catalog->findProductsForIndustrySolution($subgraph, $industrySolution) as $product) {
            $imageSource = $this->imageSourceFactory->forPreviewMixin($product, false);

            $cards[] = new ProductCard(
                $imageSource ? $this->figureFactory->forProductCard($imageSource) : null,
                Value::fromString($product->getLabel()),
                null,
                Value::fromString($product->getProperty('abstract') ?: ''),
                null
            );
        }

        return $cards;
    }

    /**
     * @return array<int,NavigationCard>
     */
    public function forIndustrySolutions(TraversableNodeInterface $industrySolutions): array
    {
        $cards = [];
        foreach ($this->findChildNodesByNodeTypeFilterString(
            $industrySolutions,
            'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolution'
        ) as $industrySolution) {
            $imageSource = $this->imageSourceFactory->forPreviewMixin($industrySolutions, false);
            $link = (new LinkFactory())->forLinkToDocument($industrySolution);

            $cards[] = new NavigationCard(
                $imageSource ? $this->figureFactory->forNavigationCard($imageSource) : null,
                Value::fromString($industrySolution->getLabel()),
                null,
                Value::fromString($industrySolution->getProperty('abstract') ?: ''),
                $link
            );
        }

        return $cards;
    }

    public function forThing(TraversableNodeInterface $thing): CardInterface
    {
        return new ArticleCard(
            null,
            Value::fromString($thing->getLabel()),
            null,
            null,
            null
        );
    }
}
