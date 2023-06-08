<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Slider extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SliderVariant $variant,
        public readonly SlotInterface $slides,
        public readonly ?SlotInterface $inlineNavigation = null,
        public readonly ?SlotInterface $headline = null
    ) {
    }
}
