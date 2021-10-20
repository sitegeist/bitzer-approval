<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Breadcrumb;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;

interface BreadcrumbInterface extends ComponentPresentationObjectInterface
{
    public function getLinks(): SlotInterface;

    public function getGlue(): IconInterface;
}
