<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MetaHeadline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadlineVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface MetaHeadlineInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): MetaHeadlineVariant;

    public function getSlice1(): ?SlotInterface;

    public function getSlice2(): ?SlotInterface;

    public function getSlice3(): ?SlotInterface;
}
