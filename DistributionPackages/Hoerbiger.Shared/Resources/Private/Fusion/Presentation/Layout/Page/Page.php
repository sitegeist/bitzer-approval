<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterInterface;

/**
 * @Flow\Proxy(false)
 */
final class Page extends AbstractComponentPresentationObject implements PageInterface
{
    private SiteHeaderInterface $siteHeader;

    private SlotInterface $content;

    private SiteFooterInterface $siteFooter;

    public function __construct(
        SiteHeaderInterface $siteHeader,
        SlotInterface $content,
        SiteFooterInterface $siteFooter
    ) {
        $this->siteHeader = $siteHeader;
        $this->content = $content;
        $this->siteFooter = $siteFooter;
    }

    public function getSiteHeader(): SiteHeaderInterface
    {
        return $this->siteHeader;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getSiteFooter(): SiteFooterInterface
    {
        return $this->siteFooter;
    }
}
