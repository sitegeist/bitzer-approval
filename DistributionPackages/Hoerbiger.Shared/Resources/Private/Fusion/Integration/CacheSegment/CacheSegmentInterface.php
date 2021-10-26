<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Integration\CacheSegment;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface CacheSegmentInterface extends ComponentPresentationObjectInterface
{
    public function getContent(): SlotInterface;
}
