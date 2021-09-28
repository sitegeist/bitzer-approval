<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Psr\Http\Message\UriInterface;

interface ButtonInterface extends ComponentPresentationObjectInterface
{
    public function getType(): ButtonType;

    public function getVariant(): ButtonVariant;

    public function getContent(): SlotInterface;

    public function getHref(): ?UriInterface;

    public function getTarget(): ?string;

    public function getRel(): ?string;
}
