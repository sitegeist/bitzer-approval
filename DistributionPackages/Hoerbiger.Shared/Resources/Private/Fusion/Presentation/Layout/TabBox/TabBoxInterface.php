<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

interface TabBoxInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): TabBoxVariant;

    public function getHeadline(): ?Headline;

    public function getTabs(): Collection;

    public function getIdentifier(): ?string;
}
