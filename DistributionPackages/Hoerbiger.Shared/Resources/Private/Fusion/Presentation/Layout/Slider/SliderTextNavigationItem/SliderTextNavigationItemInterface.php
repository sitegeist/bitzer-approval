<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderTextNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface SliderTextNavigationItemInterface extends ComponentPresentationObjectInterface
{
    public function getLabel(): string;
}
