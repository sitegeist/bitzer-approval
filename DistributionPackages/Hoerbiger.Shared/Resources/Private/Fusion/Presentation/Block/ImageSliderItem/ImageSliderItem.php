<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ImageSliderItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class ImageSliderItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $src,
        public readonly string $alt,
        public readonly string $title,
        public readonly string $orientation
    ) {
    }
}
