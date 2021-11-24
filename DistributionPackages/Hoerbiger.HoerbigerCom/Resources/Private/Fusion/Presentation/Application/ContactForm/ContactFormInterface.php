<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface ContactFormInterface extends ComponentPresentationObjectInterface
{
    public function getApplicationData(): string;

    public function getSuccessContent(): SlotInterface;

    public function getFailContent(): SlotInterface;

    public function getIsInBackend(): bool;
}
