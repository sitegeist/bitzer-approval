<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Navigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;

/**
 * @Flow\Proxy(false)
 */
final class Navigation extends AbstractComponentPresentationObject implements NavigationInterface
{
    private NavigationItems $navigationItems;

    public function __construct(
        NavigationItems $navigationItems
    ) {
        $this->navigationItems = $navigationItems;
    }

    public function getNavigationItems(): NavigationItems
    {
        return $this->navigationItems;
    }
}
