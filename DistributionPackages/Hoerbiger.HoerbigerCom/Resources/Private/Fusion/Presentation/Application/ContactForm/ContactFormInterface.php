<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface ContactFormInterface extends ComponentPresentationObjectInterface
{
    public function getApplicationData(): string;
}
