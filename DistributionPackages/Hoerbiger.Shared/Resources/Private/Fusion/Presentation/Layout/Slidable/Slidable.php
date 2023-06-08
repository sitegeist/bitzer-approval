<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Slidable extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlidableVariant $variant,
        public readonly SlotInterface $slides,
        public readonly ?SlotInterface $thumbs,
        public readonly ?SlotInterface $inlineNavigation = null,
        public readonly ?SlotInterface $dots = null,
        public readonly ?SlotInterface $headline = null,
        public readonly ?bool $isApplicationWorld = false
    ) {
    }
}
