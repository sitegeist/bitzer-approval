<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Navigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;

interface NavigationInterface extends ComponentPresentationObjectInterface
{
    public function getNavigationItems(): NavigationItems;
}
