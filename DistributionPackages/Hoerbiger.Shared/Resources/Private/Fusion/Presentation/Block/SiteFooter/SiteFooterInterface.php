<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CollectionInterface;
use Psr\Http\Message\UriInterface;

interface SiteFooterInterface extends ComponentPresentationObjectInterface
{
    public function getSiteUri(): UriInterface;

    public function getLogoUri(): UriInterface;

    public function getPrimaryNavigationItems(): CollectionInterface;

    public function getSecondaryNavigationItems(): CollectionInterface;

    public function getTertiaryNavigationItems(): CollectionInterface;

    public function getSocialLinks(): CollectionInterface;

    public function getContactFormLink(): ?ContactFormLinkInterface;
}
