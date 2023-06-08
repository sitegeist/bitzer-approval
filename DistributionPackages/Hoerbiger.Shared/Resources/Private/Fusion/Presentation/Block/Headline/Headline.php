<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Headline extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly HeadlineSize $size,
        public readonly HeadlineColor $color,
        public readonly HeadlineType $type,
        public readonly SlotInterface $content
    ) {
    }
}
