<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface DefinitionListInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): DefinitionListVariant;

    public function getContent(): SlotInterface;
}
