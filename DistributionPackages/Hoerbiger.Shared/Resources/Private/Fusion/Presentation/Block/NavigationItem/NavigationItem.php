<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class NavigationItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly Link $link,
        public readonly ?NavigationItems $subItems,
        public readonly bool $isActive
    ) {
    }
}
