<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CollectionInterface;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteFooter extends AbstractComponentPresentationObject implements SiteFooterInterface
{
    private UriInterface $siteUri;

    private UriInterface $logoUri;

    private CollectionInterface $primaryNavigationItems;

    private CollectionInterface $secondaryNavigationItems;

    private CollectionInterface $tertiaryNavigationItems;

    private CollectionInterface $socialLinks;

    private ?ContactFormLinkInterface $contactFormLink;

    public function __construct(
        UriInterface $siteUri,
        UriInterface $logoUri,
        CollectionInterface $primaryNavigationItems,
        CollectionInterface $secondaryNavigationItems,
        CollectionInterface $tertiaryNavigationItems,
        CollectionInterface $socialLinks,
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

    public function getPrimaryNavigationItems(): CollectionInterface
    {
        return $this->primaryNavigationItems;
    }

    public function getSecondaryNavigationItems(): CollectionInterface
    {
        return $this->secondaryNavigationItems;
    }

    public function getTertiaryNavigationItems(): CollectionInterface
    {
        return $this->tertiaryNavigationItems;
    }

    public function getSocialLinks(): CollectionInterface
    {
        return $this->socialLinks;
    }

    public function getContactFormLink(): ?ContactFormLinkInterface
    {
        return $this->contactFormLink;
    }
}
