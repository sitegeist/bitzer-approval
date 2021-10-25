<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteFooter extends AbstractComponentPresentationObject implements SiteFooterInterface
{
    private ?SlotInterface $socialLinks;

    private ?SlotInterface $navigationLinks;

    public function __construct(
        ?SlotInterface $socialLinks,
        ?SlotInterface $navigationLinks
    ) {
        $this->socialLinks = $socialLinks;
        $this->navigationLinks = $navigationLinks;
    }

    public function getSocialLinks(): ?SlotInterface
    {
        return $this->socialLinks;
    }

    public function getNavigationLinks(): ?SlotInterface
    {
        return $this->navigationLinks;
    }
}
