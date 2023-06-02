<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;

final class TimelineFactory extends AbstractComponentPresentationObjectFactory
{
    public function forMilestones(TraversableNodeInterface $milestones): Timeline
    {
        return new Timeline(
            Collection::fromNodes(
                $milestones->findChildNodes(),
                function (TraversableNodeInterface $milestone): Content {
                    return Content::fromNode($milestone, 'Hoerbiger.HoerbigerCom:ContentSlot');
                }
            )
        );
    }
}
