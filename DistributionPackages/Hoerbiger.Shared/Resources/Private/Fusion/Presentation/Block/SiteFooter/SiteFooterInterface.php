<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use Psr\Http\Message\UriInterface;

interface SiteFooterInterface extends ComponentPresentationObjectInterface
{
    public function getSiteUri(): UriInterface;

    public function getLogoUri(): UriInterface;

    public function getPrimaryNavigationItems(): Collection;

    public function getSecondaryNavigationItems(): Collection;

    public function getTertiaryNavigationItems(): Collection;

    public function getSocialLinks(): Collection;

    public function getContactFormLink(): ?ContactFormLinkInterface;
}
