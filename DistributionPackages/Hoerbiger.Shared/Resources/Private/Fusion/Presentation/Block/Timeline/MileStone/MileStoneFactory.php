<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline\MileStone;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class MileStoneFactory extends AbstractComponentPresentationObjectFactory
{
    public function forMilestone(TraversableNodeInterface $milestone): MileStone
    {
        return new MileStone(
            Editable::fromNodeProperty($milestone, 'year'),
            Editable::fromNodeProperty($milestone, 'headline'),
            Editable::fromNodeProperty($milestone, 'text'),
            null
        );
    }
}
