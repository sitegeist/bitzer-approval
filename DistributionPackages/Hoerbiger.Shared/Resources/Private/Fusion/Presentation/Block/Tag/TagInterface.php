<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface TagInterface extends ComponentPresentationObjectInterface
{
    public function getTagName(): string;

    public function getContent(): SlotInterface;
}
