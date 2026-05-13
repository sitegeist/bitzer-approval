<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ImageSlider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class ImageSlider extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly Icon $prevSlideIcon,
        public readonly Icon $nextSlideIcon
    ) {
    }
}
