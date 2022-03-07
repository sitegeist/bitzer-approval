<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slidable\IconNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface IconNavigationItemInterface extends ComponentPresentationObjectInterface
{
    public function getIcon(): IconInterface;

    public function getLabel(): string;
}
