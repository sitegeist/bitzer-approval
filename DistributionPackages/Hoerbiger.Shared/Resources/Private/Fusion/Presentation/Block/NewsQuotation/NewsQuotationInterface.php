<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NewsQuotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface NewsQuotationInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): NewsQuotationVariant;

    public function getText(): SlotInterface;

    public function getSpokenByCharacter(): SlotInterface;

    public function getDivision(): SlotInterface;
}
