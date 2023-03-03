<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface HeaderVideoStageInterface extends ComponentPresentationObjectInterface
{
    public function getSlides(): SlotInterface;
}
