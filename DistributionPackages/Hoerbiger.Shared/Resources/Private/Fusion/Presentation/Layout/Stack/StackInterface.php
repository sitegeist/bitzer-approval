<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stack;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface StackInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): StackVariant;

    public function getContent(): SlotInterface;
}
