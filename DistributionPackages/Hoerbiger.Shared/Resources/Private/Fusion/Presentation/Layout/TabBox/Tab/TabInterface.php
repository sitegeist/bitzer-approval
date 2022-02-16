<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\TabBox\Tab;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;

interface TabInterface extends ComponentPresentationObjectInterface
{
    public function getLabel(): SlotInterface;

    public function getTabIcon(): ?IconInterface;

    public function getAccordionIcon(): ?IconInterface;

    public function getContent(): SlotInterface;
}
