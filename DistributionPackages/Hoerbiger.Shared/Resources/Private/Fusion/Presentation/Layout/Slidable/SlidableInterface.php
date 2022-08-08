<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SlidableInterface extends ComponentPresentationObjectInterface
{
    public function getSlides(): SlotInterface;

    public function getThumbs(): ?SlotInterface;

    public function getInlineNavigation(): ?SlotInterface;

    public function getDots(): ?SlotInterface;

    public function getIsApplicationWorld(): ?bool;
}
