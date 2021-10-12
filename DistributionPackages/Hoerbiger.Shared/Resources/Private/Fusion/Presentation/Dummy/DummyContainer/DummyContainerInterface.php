<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Dummy\DummyContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface DummyContainerInterface extends ComponentPresentationObjectInterface
{
    public function getSampleImageUri(): UriInterface;

    public function getContent(): SlotInterface;
}
