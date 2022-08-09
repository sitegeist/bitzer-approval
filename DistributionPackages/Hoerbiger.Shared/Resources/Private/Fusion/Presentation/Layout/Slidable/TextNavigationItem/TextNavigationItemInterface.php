<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slidable\TextNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface TextNavigationItemInterface extends ComponentPresentationObjectInterface
{
    public function getLabel(): string;
}
