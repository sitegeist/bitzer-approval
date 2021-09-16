<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterInterface;

interface PageInterface extends ComponentPresentationObjectInterface
{
    public function getSiteHeader(): SiteHeaderInterface;

    public function getContent(): SlotInterface;

    public function getSiteFooter(): SiteFooterInterface;
}
