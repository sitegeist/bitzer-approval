<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class ListItemFactory extends AbstractComponentPresentationObjectFactory
{
    public function forDefinition(TraversableNodeInterface $definitionNode): ListItem
    {
        return new ListItem(
            Editable::fromNodeProperty($definitionNode, 'term'),
            Editable::fromNodeProperty($definitionNode, 'definition')
        );
    }
}
