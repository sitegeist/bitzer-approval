<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SiteFooterInterface extends ComponentPresentationObjectInterface
{
    public function getSocialLinks(): ?Collection;

    public function getNavigationLinks(): ?SlotInterface;

    public function getContactFormLink(): ?ContactFormLinkInterface;
}
