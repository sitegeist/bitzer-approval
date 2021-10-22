<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;

interface StageInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): StageVariant;

    public function getHeadline(): ?HeadlineInterface;

    public function getSubheadline(): ?SlotInterface;

    public function getText(): ?TextInterface;

    public function getMedia(): ?SlotInterface;

    public function getMediaBackground(): ?SlotInterface;
}
