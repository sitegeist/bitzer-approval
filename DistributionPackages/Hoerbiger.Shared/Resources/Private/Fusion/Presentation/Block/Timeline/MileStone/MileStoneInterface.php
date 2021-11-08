<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline\MileStone;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface MileStoneInterface extends ComponentPresentationObjectInterface
{
    public function getYear(): SlotInterface;

    public function getLabel(): SlotInterface;

    public function getDescription(): SlotInterface;

    public function getFigures(): ?SlotInterface;
}
