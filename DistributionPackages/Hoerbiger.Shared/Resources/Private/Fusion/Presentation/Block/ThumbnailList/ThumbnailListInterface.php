<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ThumbnailList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface ThumbnailListInterface extends ComponentPresentationObjectInterface
{
    public function getHeadline(): ?HeadlineInterface;

    public function getThumbnailList(): SlotInterface;
}
