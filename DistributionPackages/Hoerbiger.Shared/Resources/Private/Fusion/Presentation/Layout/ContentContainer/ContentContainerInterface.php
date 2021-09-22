<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\ContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface ContentContainerInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): ContentContainerVariant;

    public function getContent(): SlotInterface;
}
