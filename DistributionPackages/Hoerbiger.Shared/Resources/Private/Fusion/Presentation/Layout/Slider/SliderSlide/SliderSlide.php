<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderSlide;

use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class SliderSlide extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly StageVariant $variant,
        public readonly ?SlotInterface $media,
        public readonly ?SlotInterface $headline,
        public readonly ?SlotInterface $text,
        public readonly ?SlotInterface $button = null
    ) {
    }
}
