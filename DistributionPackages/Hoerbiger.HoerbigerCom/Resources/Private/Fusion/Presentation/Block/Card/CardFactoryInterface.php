<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

interface CardFactoryInterface
{
    public function forThing(TraversableNodeInterface $thing): CardInterface;
}
