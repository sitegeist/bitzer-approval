<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteFooter extends AbstractComponentPresentationObject implements SiteFooterInterface
{
    private ?Collection $socialLinks;

    private ?SlotInterface $navigationLinks;

    private ?ContactFormLinkInterface $contactFormLink;

    public function __construct(
        ?Collection $socialLinks,
        ?SlotInterface $navigationLinks,
        ?ContactFormLinkInterface $contactFormLink
    ) {
        $this->socialLinks = $socialLinks;
        $this->navigationLinks = $navigationLinks;
        $this->contactFormLink = $contactFormLink;
    }

    public function getSocialLinks(): ?Collection
    {
        return $this->socialLinks;
    }

    public function getNavigationLinks(): ?SlotInterface
    {
        return $this->navigationLinks;
    }

    public function getContactFormLink(): ?ContactFormLinkInterface
    {
        return $this->contactFormLink;
    }
}
