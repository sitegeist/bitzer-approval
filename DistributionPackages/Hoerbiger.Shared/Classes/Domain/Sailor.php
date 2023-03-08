<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Domain;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

class Sailor
{
    /**
     * @Flow\Inject
     * @var NodeTypeConstraintFactory
     */
    protected $nodeTypeConstraintFactory;

    /**
     * @return array<int,Anchor>
     */
    public function findAvailableNeighbouringAnchors(NodeInterface $contentNode): array
    {
        /** @var TraversableNodeInterface $contentNode */
        $documentNode = $contentNode->findParentNode();

        while (!$documentNode->getNodeType()->isOfType('Neos.Neos:Document') && !$documentNode->isRoot()) {
            $documentNode = $documentNode->findParentNode();
        }

        return $this->findAvailableAnchorsForDocument($documentNode);
    }


    /**
     * @return array<int,Anchor>
     */
    public function findAvailableAnchorsForDocument(TraversableNodeInterface $document) : array
    {
        $availableAnchors = [];
        $firstLevelContentCollections = $this->findFirstLevelContentCollections($document);

        foreach ($firstLevelContentCollections as $firstLevelContentCollection) {
            $availableAnchors = array_merge(
                $availableAnchors,
                $this->findAnchorsInCollection($firstLevelContentCollection)
            );
        }

        return $availableAnchors;
    }

    /**
     * @return array<int,Anchor>
     */
    protected function findAnchorsInCollection(TraversableNodeInterface $contentCollectionNode): array
    {
        $anchors = [];
        foreach ($contentCollectionNode->findChildNodes() as $contentNode) {
            $anchorID = $contentNode->getProperty('anchorID');
            // TBD
            if ($anchorID) {
                $anchors[] = new Anchor(
                    str_replace(
                        [' ' ],
                        '',
                        strip_tags($anchorID)
                    ),
                    $anchorID
                );
            }
        }
        return $anchors;
    }

    /**
     * @return array<int,TraversableNodeInterface>
     */
    protected function findFirstLevelContentCollections(TraversableNodeInterface $documentNode): array
    {
        $firstLevelContentCollections = [];

        foreach ($documentNode->findChildNodes(
            $this->nodeTypeConstraintFactory->parseFilterString(
                'Neos.Neos:ContentCollection'
            )
        ) as $collection
        ) {
            /** @var TraversableNodeInterface $collection */
            if ($collection->findParentNode() === $documentNode) {
                $firstLevelContentCollections[] = $collection;
            }
        }

        return $firstLevelContentCollections;
    }

    private function cleanString(string $string): ?string
    {
        $string = str_replace(' ', '-', $string);

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}
