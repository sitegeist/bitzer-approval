<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\AnchorNavigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\Link;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface AnchorNavigationInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): AnchorNavigationVariant;

    /**
     * @return array<int,Link>
     */
    public function getAnchors(): array;
}
