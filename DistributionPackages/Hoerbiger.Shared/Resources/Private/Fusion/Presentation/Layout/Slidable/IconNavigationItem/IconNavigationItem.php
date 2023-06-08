<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slidable\IconNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class IconNavigationItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly Icon $icon,
        public readonly string $label
    ) {
    }
}
