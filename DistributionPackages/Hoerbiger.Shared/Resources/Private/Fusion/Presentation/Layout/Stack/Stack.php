<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stack;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Stack extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly StackVariant $variant,
        public readonly SlotInterface $content
    ) {
    }
}
