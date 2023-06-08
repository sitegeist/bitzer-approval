<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\AnchorNavigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class AnchorNavigation extends AbstractComponentPresentationObject
{
    /**
     * @param array<int,Link> $anchors
     */
    public function __construct(
        public readonly AnchorNavigationVariant $variant,
        public readonly array $anchors
    ) {
    }
}
