<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Card\ProductCard\ProductCard;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class CardFactory extends AbstractComponentPresentationObjectFactory
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
}
