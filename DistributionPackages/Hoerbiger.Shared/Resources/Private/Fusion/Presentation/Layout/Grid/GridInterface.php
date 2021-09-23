<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Grid;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface GridInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): GridVariant;

    public function getContent(): SlotInterface;
}
