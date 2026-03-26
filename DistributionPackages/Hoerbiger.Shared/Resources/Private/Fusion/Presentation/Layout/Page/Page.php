<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Page extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SiteHeader $siteHeader,
        public readonly SlotInterface $content,
        public readonly bool $hasFooter
    ) {
    }
}
