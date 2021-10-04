<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface ListItemInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): ListItemVariant;

    public function getTerm(): SlotInterface;

    public function getDefinition(): SlotInterface;
}
