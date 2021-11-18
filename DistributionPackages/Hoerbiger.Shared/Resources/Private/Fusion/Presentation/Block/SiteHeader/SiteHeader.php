<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;

/**
 * @Flow\Proxy(false)
 */
final class SiteHeader extends AbstractComponentPresentationObject implements SiteHeaderInterface
{
    private NavigationItems $mainNavigation;

    private SlotInterface $search;

    private NavigationItems $languageNavigation;

    public function __construct(
        NavigationItems $mainNavigation,
        SlotInterface $search,
        NavigationItems $languageNavigation
    ) {
        $this->mainNavigation = $mainNavigation;
        $this->search = $search;
        $this->languageNavigation = $languageNavigation;
    }

    public function getMainNavigation(): NavigationItems
    {
        return $this->mainNavigation;
    }

    public function getSearch(): SlotInterface
    {
        return $this->search;
    }

    public function getLanguageNavigation(): NavigationItems
    {
        return $this->languageNavigation;
    }
}
