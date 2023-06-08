<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\TabBox\Tab;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;

#[Flow\Proxy(false)]
final class Tab extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $label,
        public readonly ?Icon $tabIcon,
        public readonly ?Icon $accordionIcon,
        public readonly SlotInterface $content
    ) {
    }
}
