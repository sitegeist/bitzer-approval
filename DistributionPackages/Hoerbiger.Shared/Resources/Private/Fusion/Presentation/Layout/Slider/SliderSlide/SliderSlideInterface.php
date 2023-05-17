<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderSlide;

use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SliderSlideInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): StageVariant;

    public function getMedia(): ?SlotInterface;

    public function getHeadline(): ?SlotInterface;

    public function getText(): ?SlotInterface;

    public function getButton(): ?SlotInterface;
}
