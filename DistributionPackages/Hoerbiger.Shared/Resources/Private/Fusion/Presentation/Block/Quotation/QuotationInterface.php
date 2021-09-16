<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Quotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface QuotationInterface extends ComponentPresentationObjectInterface
{
    public function getText(): SlotInterface;

    public function getSpokenByCharacter(): SlotInterface;
}
