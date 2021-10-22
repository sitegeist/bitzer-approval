<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Breadcrumb;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class BreadcrumbFactory extends AbstractComponentPresentationObjectFactory
{
    public function forPage(TraversableNodeInterface $currentDocument, NodeInterface $siteNode): BreadcrumbInterface
    {
        $nodesOnPath = $siteNode->getContext()->getNodesOnPath($siteNode, $currentDocument);
        $current = array_pop($nodesOnPath);

        $links = Collection::fromNodes(
            TraversableNodes::fromArray($nodesOnPath),
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
