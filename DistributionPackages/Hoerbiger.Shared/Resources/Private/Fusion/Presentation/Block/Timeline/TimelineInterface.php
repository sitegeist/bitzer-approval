<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CollectionInterface;

interface TimelineInterface extends ComponentPresentationObjectInterface
{
    public function getMilestones(): CollectionInterface;
}
