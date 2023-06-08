<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderTextNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class SliderTextNavigationItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $label
    ) {
    }
}
