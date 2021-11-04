<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Row;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

interface RowInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): RowVariant;

    public function getContent(): Collection;
}
