<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Breadcrumb;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class BreadcrumbFactory extends AbstractComponentPresentationObjectFactory
{
    public function forPage(TraversableNodeInterface $currentDocument, ContentContext $subgraph): Breadcrumb
    {
        $nodesOnPath = $subgraph->getNodesOnPath(
            $subgraph->getCurrentSiteNode(),
            $currentDocument
        );
        $current = array_pop($nodesOnPath);

        $links = Collection::fromNodes(
            TraversableNodes::fromArray(array_filter(array_map(function ($node) {
                if ( !in_array($node->getNodeType(), [
                    'Hoerbiger.HoerbigerCom.PressArchive:Document.Magazine.Yearbook',
                    'Hoerbiger.HoerbigerCom.PressArchive:Document.Magazine.Hanns',
                    'Hoerbiger.HoerbigerCom.PressArchive:Document.Magazine.Press',
                ]) ) {

                    return $node;
                }
            }, $nodesOnPath))),
            function($node) {
                return (new LinkFactory())->forBreadcrumbItem($node);
            }
        );

        return new Breadcrumb(
            $links,
            Value::fromString($current ? $current->getLabel() : ''),
            new Icon('shared', 'chevronRight'),
            new Icon('shared', 'chevronLeft')
        );
    }
}
