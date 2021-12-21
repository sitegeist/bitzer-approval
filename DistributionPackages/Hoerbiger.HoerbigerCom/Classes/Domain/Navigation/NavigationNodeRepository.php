<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Navigation;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;

/**
 * The content context factory for Most Wanted concerns
 *
 * @Flow\Scope("singleton")
 */
final class NavigationNodeRepository
{
    private NodeTypeConstraintFactory $nodeTypeConstraintFactory;

    public function __construct(NodeTypeConstraintFactory $nodeTypeConstraintFactory)
    {
        $this->nodeTypeConstraintFactory = $nodeTypeConstraintFactory;
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findMainNavigationDocuments(TraversableNodeInterface $site): TraversableNodes
    {
        $documents = $site->findChildNodes(
            $this->nodeTypeConstraintFactory->parseFilterString(
                'Hoerbiger.HoerbigerCom:Document,!Hoerbiger.Shared:Tag.ExcludeFromNavigation'
            )
        )->toArray();

        return TraversableNodes::fromArray(array_filter(
            $documents,
            function (TraversableNodeInterface $document): bool {
                /** @var Node $document */
                return !$document->isHiddenInIndex();
            }
        ));
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findHeaderNavigationDocuments(TraversableNodeInterface $site): TraversableNodes
    {
        return TraversableNodes::fromArray($site->getProperty('headerNavigation') ?: []);
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findFooterNavigationDocuments(TraversableNodeInterface $site): TraversableNodes
    {
        return TraversableNodes::fromArray($site->getProperty('footerNavigation') ?: []);
    }
}
