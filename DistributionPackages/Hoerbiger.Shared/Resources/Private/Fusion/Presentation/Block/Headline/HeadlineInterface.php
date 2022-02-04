<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface HeadlineInterface extends ComponentPresentationObjectInterface
{
    public function getSize(): HeadlineSize;

    public function getColor(): HeadlineColor;

    public function getType(): HeadlineType;

    public function getContent(): SlotInterface;
}
