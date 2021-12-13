<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Media\Domain\Repository\DocumentRepository;

/**
 * The asset directory domain repository
 */
class AssetDirectory extends Node
{
    /**
     * @Flow\Inject(lazy=false)
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @Flow\Inject(lazy=false)
     * @var AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @Flow\Inject(lazy=false)
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject(lazy=false)
     * @var AssetAdapterFactory
     */
    protected $assetAdapterFactory;

    /**
     * @Flow\Inject
     * @var NodeTypeConstraintFactory
     */
    protected $nodeTypeConstraintFactory;

    /**
     * @return array<int,AssetCollectionAdapter>
     */
    public function getChildNodes($nodeTypeFilter = null, $limit = null, $offset = null): array
    {
        if ($nodeTypeFilter) {
            $nodeTypeConstraints = $this->nodeTypeConstraintFactory->parseFilterString($nodeTypeFilter);
            if (!$nodeTypeConstraints->matches(AssetCollectionAdapter::nodeTypeName())) {
                return [];
            }
        }

        $assetCollectionAdapters = [];
        $index = 0;
        foreach ($this->assetCollectionRepository->findAll() as $assetCollection) {
            $assetCollectionAdapters[] = new AssetCollectionAdapter(
                $assetCollection,
                $this->persistenceManager->getIdentifierByObject($assetCollection),
                $this,
                $index,
                $this->documentRepository,
                $this->nodeTypeManager,
                $this->assetAdapterFactory
            );

            $index += 128;
        }

        return $assetCollectionAdapters;
    }

    public function findNamedChildNode(NodeName $nodeName): AssetCollectionAdapter
    {
        $assetCollection = $this->assetCollectionRepository->findByIdentifier((string)$nodeName);
        if (!$assetCollection instanceof AssetCollection) {
            throw new NodeException(sprintf('Child node named "%s" not found', $nodeName), 1543406006);
        }
        return new AssetCollectionAdapter(
            $assetCollection,
            $this->persistenceManager->getIdentifierByObject($assetCollection),
            $this,
            0,
            $this->documentRepository,
            $this->nodeTypeManager,
            $this->assetAdapterFactory
        );
    }

    /**
     * @return TraversableNodes<int,AssetCollectionAdapter>
     */
    public function findChildNodes(
        NodeTypeConstraints $nodeTypeConstraints = null,
        int $limit = null,
        int $offset = null
    ): TraversableNodes {
        if (!$nodeTypeConstraints || $nodeTypeConstraints->matches(AssetCollectionAdapter::nodeTypeName())) {
            return parent::findChildNodes($nodeTypeConstraints, $limit, $offset);
        }

        return TraversableNodes::fromArray([]);
    }
}
