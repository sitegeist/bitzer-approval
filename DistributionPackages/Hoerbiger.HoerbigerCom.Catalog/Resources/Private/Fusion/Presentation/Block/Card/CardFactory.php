<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
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
