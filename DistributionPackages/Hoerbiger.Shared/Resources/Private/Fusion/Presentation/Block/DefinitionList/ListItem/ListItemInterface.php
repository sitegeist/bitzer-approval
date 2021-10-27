<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface ListItemInterface extends ComponentPresentationObjectInterface
{
    public function getTerm(): SlotInterface;

    public function getDefinition(): SlotInterface;
}
