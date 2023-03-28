<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SliderInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): SliderVariant;

    public function getSlides(): SlotInterface;

    public function getInlineNavigation(): ?SlotInterface;

    public function getHeadline(): ?SlotInterface;
}
