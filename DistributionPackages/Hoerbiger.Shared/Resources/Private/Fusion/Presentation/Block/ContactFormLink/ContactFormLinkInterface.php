<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ContactFormLink;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonInterface;

interface ContactFormLinkInterface extends ComponentPresentationObjectInterface
{
    public function getContent(): SlotInterface;

    public function getButton(): ButtonInterface;
}
