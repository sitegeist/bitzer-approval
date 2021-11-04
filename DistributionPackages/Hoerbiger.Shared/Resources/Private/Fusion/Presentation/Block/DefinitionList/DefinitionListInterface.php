<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

interface DefinitionListInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): DefinitionListVariant;

    public function getDefinitions(): Collection;
}
