<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;
use Hoerbiger\Shared\Presentation\Block\Navigation\NavigationInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteHeader extends AbstractComponentPresentationObject implements SiteHeaderInterface
{
    private NavigationInterface $mainNavigation;

    private SlotInterface $search;

    private SlotInterface $languageNavigation;

    public function __construct(
        NavigationInterface $mainNavigation,
        SlotInterface $search,
        SlotInterface $languageNavigation
    ) {
        $this->mainNavigation = $mainNavigation;
        $this->search = $search;
        $this->languageNavigation = $languageNavigation;
    }

    public function getMainNavigation(): NavigationInterface
    {
        return $this->mainNavigation;
    }

    public function getSearch(): SlotInterface
    {
        return $this->search;
    }

    public function getLanguageNavigation(): SlotInterface
    {
        return $this->languageNavigation;
    }
}
