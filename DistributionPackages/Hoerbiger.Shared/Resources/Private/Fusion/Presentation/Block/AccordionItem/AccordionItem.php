<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\AccordionItem;

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\StringLike;

#[Flow\Proxy(false)]
final class AccordionItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly AccordionItemVariant $variant,
        public readonly StringLike $header,
        public readonly SlotInterface $content,
        public readonly Icon $dashIcon,
        public readonly Icon $plusIcon,
        public readonly bool $isOpen,
        public readonly bool $inBackend
    ) {
    }
}
