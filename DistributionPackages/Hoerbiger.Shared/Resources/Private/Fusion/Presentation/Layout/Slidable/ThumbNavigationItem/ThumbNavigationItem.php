<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;

#[Flow\Proxy(false)]
final class ThumbNavigationItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ?Figure $figure,
        public readonly string $label,
        public readonly ?ThumbNavigationItemType $type
    ) {
    }
}
