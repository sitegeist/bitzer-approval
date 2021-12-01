<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;

/**
 * @Flow\Proxy(false)
 */
final class SiteHeader extends AbstractComponentPresentationObject implements SiteHeaderInterface
{
    private ?NavigationItems $mainNavigation;

    private SlotInterface $search;

    private NavigationItems $languageNavigation;

    private Icon $rootLevelIcon;

    private Icon $subLevelIcon;

    public function __construct(
        ?NavigationItems $mainNavigation,
        SlotInterface $search,
        NavigationItems $languageNavigation,
        Icon $rootLevelIcon,
        Icon $subLevelIcon
    ) {
        $this->mainNavigation = $mainNavigation;
        $this->search = $search;
        $this->languageNavigation = $languageNavigation;
        $this->rootLevelIcon = $rootLevelIcon;
        $this->subLevelIcon = $subLevelIcon;
    }

    public function getMainNavigation(): ?NavigationItems
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

    public function getRootLevelIcon(): Icon
    {
        return $this->rootLevelIcon;
    }

    public function getSubLevelIcon(): Icon
    {
        return $this->subLevelIcon;
    }
}
