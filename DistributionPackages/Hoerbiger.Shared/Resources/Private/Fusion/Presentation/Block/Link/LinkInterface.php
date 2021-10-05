<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface LinkInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): LinkVariant;

    public function getHref(): UriInterface;

    public function getTarget(): LinkTarget;

    public function getRel(): ?string;

    public function getContent(): ?SlotInterface;
}
