<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Layout\TabBox\Tab\Tab;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;

final class TabBoxFactory extends AbstractComponentPresentationObjectFactory
{
    public function forTabBox(TraversableNodeInterface $tabBox): TabBox
    {
        return new TabBox(
            TabBoxVariant::regular(),
            Collection::fromNodes(
                $tabBox->findChildNodes(),
                function(TraversableNodeInterface $tabNode) {
                    return new Tab(
                        Content::fromNode($tabNode, 'Hoerbiger.HoerbigerCom:TabLabel'),
                        new Icon('shared', 'arrowRight'),
                        Content::fromNode($tabNode, 'Hoerbiger.HoerbigerCom:ContentSlot')
                    );
                }
            )
        );
    }
}
