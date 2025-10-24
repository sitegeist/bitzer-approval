<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\TeaserItem;

use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\StringLike;

#[Flow\Proxy(false)]
final class TeaserItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly StringLike $header,
        public readonly Figure $figure,
        public readonly Button $button,
        public readonly bool $isFontColorBlack
    ) {
    }
}
