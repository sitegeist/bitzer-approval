<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Aspect;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\AssetAdapterFactory;
use Hoerbiger\Shared\Domain\Asset\AssetDirectoryRepository;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Media\Domain\Repository\DocumentRepository;

/**
 * The virtual content graph.
 * Allows extension of the content context without extending it
 *
 * @see NodeIndexerInterface::indexNode
 * @see Context::getNodeByIdentifier
 *
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
final class VirtualContentGraph
{
    private AssetCollectionRepository $assetCollectionRepository;

    private DocumentRepository $documentRepository;

    private NodeTypeManager $nodeTypeManager;

    private PersistenceManagerInterface $persistenceManagerInterface;

    private AssetDirectoryRepository $assetDirectoryRepository;

    private AssetAdapterFactory $assetAdapterFactory;

    private ?Node $nodeToBeIndexed = null;

    public function __construct(
        AssetCollectionRepository $assetCollectionRepository,
        DocumentRepository $documentRepository,
        NodeTypeManager $nodeTypeManager,
        PersistenceManagerInterface $persistenceManagerInterface,
        AssetDirectoryRepository $assetDirectoryRepository,
        AssetAdapterFactory $assetAdapterFactory
    ) {
        $this->assetCollectionRepository = $assetCollectionRepository;
        $this->documentRepository = $documentRepository;
        $this->nodeTypeManager = $nodeTypeManager;
        $this->persistenceManagerInterface = $persistenceManagerInterface;
        $this->assetDirectoryRepository = $assetDirectoryRepository;
        $this->assetAdapterFactory = $assetAdapterFactory;
    }

    /**
     * @Flow\Before("method(Flowpack\ElasticSearch\ContentRepositoryAdaptor\Indexer\NodeIndexer->indexNode())")
     * @param JoinPointInterface $joinPoint
     */
    public function registerIndexedNode(JoinPointInterface $joinPoint): void
    {
        $this->nodeToBeIndexed = $joinPoint->getMethodArgument('node');
    }

    /**
     * @Flow\Around("method(Neos\ContentRepository\Domain\Service\Context->getNodeByIdentifier())")
     */
    public function getAdapterByIdentifier(JoinPointInterface $joinPoint): ?TraversableNodeInterface
    {
        $foundNode = $joinPoint->getAdviceChain()->proceed($joinPoint);

        if (!$foundNode && $this->nodeToBeIndexed) {
            /** @var Context $requestedContext */
            $requestedContext = $joinPoint->getProxy();

            if ($this->requestedNodeMatchesNodeToBeIndexed(
                $requestedContext,
                NodeAggregateIdentifier::fromString($joinPoint->getMethodArgument('identifier'))
            )) {
                return $this->nodeToBeIndexed;
            }
        }

        return $foundNode;
    }

    private function requestedNodeMatchesNodeToBeIndexed(
        Context $requestedContext,
        NodeAggregateIdentifier $requestedNodeAggregateIdentifier
    ): bool {
        if (!$this->nodeToBeIndexed) {
            return false;
        }
        $requestedContextProperties = $requestedContext->getProperties();
        $availableContextProperties = $this->nodeToBeIndexed->getContext()->getProperties();
        // The ES adapter sets this for whatever reason and we don't care
        unset($requestedContextProperties['invisibleContentShown']);
        unset($availableContextProperties['invisibleContentShown']);

        return $this->nodeToBeIndexed->getNodeAggregateIdentifier()->equals($requestedNodeAggregateIdentifier)
            && $availableContextProperties == $requestedContextProperties;
    }
}
