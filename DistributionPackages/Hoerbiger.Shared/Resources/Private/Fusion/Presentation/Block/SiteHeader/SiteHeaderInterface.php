<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Navigation\NavigationInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SiteHeaderInterface extends ComponentPresentationObjectInterface
{
    public function getMainNavigation(): NavigationInterface;

    public function getSearch(): SlotInterface;

    public function getLanguageNavigation(): SlotInterface;
}
