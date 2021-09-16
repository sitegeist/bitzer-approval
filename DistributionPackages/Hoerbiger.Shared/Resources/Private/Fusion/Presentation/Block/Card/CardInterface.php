<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface CardInterface extends ComponentPresentationObjectInterface
{
    public function getMedia(): ?SlotInterface;

    public function getHeader(): ?SlotInterface;

    public function getSubheader(): ?SlotInterface;

    public function getSupportingText(): ?SlotInterface;

    public function getButtons(): ?SlotInterface;
}
