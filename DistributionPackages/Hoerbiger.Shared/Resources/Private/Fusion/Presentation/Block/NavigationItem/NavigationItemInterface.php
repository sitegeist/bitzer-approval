<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;

interface NavigationItemInterface extends ComponentPresentationObjectInterface
{
    public function getLink(): LinkInterface;

    public function getSubItems(): ?NavigationItems;

    public function getIsActive(): bool;
}
