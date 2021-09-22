<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;

interface IntroSectionInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): IntroSectionVariant;

    public function getHeadline(): HeadlineInterface;

    public function getSubheadline(): ?SlotInterface;

    public function getText(): TextInterface;

    public function getMedia(): SlotInterface;
}
