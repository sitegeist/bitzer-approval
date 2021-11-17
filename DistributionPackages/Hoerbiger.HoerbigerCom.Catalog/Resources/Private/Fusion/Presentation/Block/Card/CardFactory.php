<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Card\ProductCard\ProductCard;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    /**
     * @return array<int,ProductCard>
     */
    public function forIndustrySolutionProducts(TraversableNodeInterface $industrySolution): array
    {
        return [];
    }

    /**
     * @return array<int,NavigationCard>
     */
    public function forIndustrySolutions(TraversableNodeInterface $industrySolutions): array
    {
        return [];
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
