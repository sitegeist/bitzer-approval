<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteFooter extends AbstractComponentPresentationObject implements SiteFooterInterface
{
    private UriInterface $siteUri;

    private UriInterface $logoUri;

    private Collection $primaryNavigationItems;

    private Collection $secondaryNavigationItems;

    private Collection $tertiaryNavigationItems;

    private Collection $socialLinks;

    private ?ContactFormLinkInterface $contactFormLink;

    public function __construct(
        UriInterface $siteUri,
        UriInterface $logoUri,
        Collection $primaryNavigationItems,
        Collection $secondaryNavigationItems,
        Collection $tertiaryNavigationItems,
        Collection $socialLinks,
        ?ContactFormLinkInterface $contactFormLink
    ) {
        $this->siteUri = $siteUri;
        $this->logoUri = $logoUri;
        $this->primaryNavigationItems = $primaryNavigationItems;
        $this->secondaryNavigationItems = $secondaryNavigationItems;
        $this->tertiaryNavigationItems = $tertiaryNavigationItems;
        $this->socialLinks = $socialLinks;
        $this->contactFormLink = $contactFormLink;
    }

    public function getSiteUri(): UriInterface
    {
        return $this->siteUri;
    }

    public function getLogoUri(): UriInterface
    {
        return $this->logoUri;
    }

    public function getPrimaryNavigationItems(): Collection
    {
        return $this->primaryNavigationItems;
    }

    public function getSecondaryNavigationItems(): Collection
    {
        return $this->secondaryNavigationItems;
    }

    public function getTertiaryNavigationItems(): Collection
    {
        return $this->tertiaryNavigationItems;
    }

    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function getContactFormLink(): ?ContactFormLinkInterface
    {
        return $this->contactFormLink;
    }
}
