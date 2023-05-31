<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;

final class DefinitionListFactory extends AbstractComponentPresentationObjectFactory
{
    public function forDefinitionList(TraversableNodeInterface $definitionList): DefinitionList
    {
        return new DefinitionList(
            DefinitionListVariant::VARIANT_REGULAR,
            Collection::fromNodes(
                $definitionList->findChildNodes(),
                function(TraversableNodeInterface $definitionNode) {
                    return Content::fromNode($definitionNode, 'Hoerbiger.HoerbigerCom:ContentSlot');
                }
            )
        );
    }
}
