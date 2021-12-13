<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Domain\ContentStream\ContentStreamIdentifier;
use Neos\ContentRepository\Domain\ContentSubgraph\NodePath;
use Neos\ContentRepository\Domain\Model\ArrayPropertyCollection;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeTemplate;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\PropertyCollectionInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Utility\NodePaths;
use Neos\ContentRepository\Exception\NodeException;
use Neos\ContentRepository\Exception\NodeMethodIsUnsupported;
use Neos\EventSourcedContentRepository\Domain\Context\NodeAggregate\OriginDimensionSpacePoint;
use Neos\EventSourcedContentRepository\Domain\ValueObject\PropertyName;

/**
 * The abstract media node adapter
 */
abstract class AbstractMediaNodeAdapter implements NodeInterface, TraversableNodeInterface
{
    protected AssetDirectory $assetDirectory;

    protected string $identifier;

    protected string $path;

    protected int $index;

    protected NodeType $nodeType;

    /**
     * @var ArrayPropertyCollection<string,mixed>
     */
    protected ArrayPropertyCollection $properties;

    /**
     * @param ArrayPropertyCollection<string,mixed> $properties
     */
    public function __construct(
        AssetDirectory $assetDirectory,
        string $identifier,
        string $path,
        int $index,
        NodeType $nodeType,
        ArrayPropertyCollection $properties
    ) {
        $this->assetDirectory = $assetDirectory;
        $this->identifier = $identifier;
        $this->path = $path;
        $this->index = $index;
        $this->nodeType = $nodeType;
        $this->properties = $properties;
    }

    public function setName($newName): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setName');
    }

    public function getName(): string
    {
        return $this->identifier;
    }

    public function setProperty($propertyName, $value): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setProperty');
    }

    public function hasProperty($propertyName): bool
    {
        return $this->properties->offsetExists($propertyName);
    }

    public function getProperty($propertyName)
    {
        return $this->properties[$propertyName] ?? null;
    }

    public function removeProperty($propertyName): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('removeProperty');
    }

    /**
     * @return PropertyCollectionInterface<string,mixed>
     */
    public function getProperties(): PropertyCollectionInterface
    {
        return $this->properties;
    }

    public function getPropertyNames(): array
    {
        return array_keys($this->properties->getArrayCopy());
    }

    public function setContentObject($contentObject)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setContentObject');
    }

    public function getContentObject()
    {
        /** @phpstan-ignore-next-line */
        return null;
    }

    public function unsetContentObject()
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('unsetContentObject');
    }

    public function setNodeType(NodeType $nodeType)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setNodeType');
    }

    public function getNodeType(): NodeType
    {
        return $this->nodeType;
    }

    public function setHidden($hidden): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setHidden');
    }

    public function isHidden(): bool
    {
        return false;
    }

    public function setHiddenBeforeDateTime(\DateTimeInterface $dateTime = null)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setHiddenBeforeDateTime');
    }

    public function getHiddenBeforeDateTime(): ?\DateTimeInterface
    {
        return null;
    }

    public function setHiddenAfterDateTime(\DateTimeInterface $dateTime = null)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setHiddenAfterDateTime');
    }

    public function getHiddenAfterDateTime(): ?\DateTimeInterface
    {
        return null;
    }

    public function setHiddenInIndex($hidden): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setHiddenInIndex');
    }

    public function isHiddenInIndex(): bool
    {
        return false;
    }

    /**
     * @param array<int,mixed> $accessRoles
     */
    public function setAccessRoles(array $accessRoles): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setAccessRoles');
    }

    /**
     * @return null|array<int,mixed>
     */
    public function getAccessRoles(): ?array
    {
        return null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getContextPath(): string
    {
        return NodePaths::generateContextPath(
            $this->path,
            $this->getContext()->getWorkspaceName(),
            $this->getContext()->getDimensions()
        );
    }

    public function getDepth(): int
    {
        return NodePaths::getPathDepth($this->path);
    }

    public function setWorkspace(Workspace $workspace): void
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setWorkspace');
    }

    public function getWorkspace(): Workspace
    {
        return $this->getContext()->getWorkspace();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIndex($index)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setIndex');
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function createNode($name, NodeType $nodeType = null, $identifier = null)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('createNode');
    }

    public function createSingleNode($name, NodeType $nodeType = null, $identifier = null)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('createSingleNode');
    }

    public function createNodeFromTemplate(NodeTemplate $nodeTemplate, $nodeName = null)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('createNodeFromTemplate');
    }

    public function remove()
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('remove');
    }

    public function setRemoved($removed)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('setRemoved');
    }

    public function isRemoved(): bool
    {
        return false;
    }

    public function isVisible(): bool
    {
        return true;
    }

    public function isAccessible(): bool
    {
        return true;
    }

    public function hasAccessRestrictions(): bool
    {
        return false;
    }

    public function isNodeTypeAllowedAsChildNode(NodeType $nodeType): bool
    {
        return $this->getNodeType()->allowsChildNodeType($nodeType);
    }

    public function moveBefore(NodeInterface $referenceNode)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('moveBefore');
    }

    public function moveAfter(NodeInterface $referenceNode)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('moveAfter');
    }

    public function moveInto(NodeInterface $referenceNode)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('moveInto');
    }

    public function copyBefore(NodeInterface $referenceNode, $nodeName)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('copyBefore');
    }

    public function copyAfter(NodeInterface $referenceNode, $nodeName)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('copyAfter');
    }

    public function copyInto(NodeInterface $referenceNode, $nodeName)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('copyInto');
    }

    public function getNodeData()
    {
        throw MethodIsNotSupportedInAdapter::becauseAdaptersEncapsulateNoNodeDataObject('getNodeData');
    }

    public function getContext(): Context
    {
        return $this->assetDirectory->getContext();
    }

    /**
     * @return array<string,mixed>
     */
    public function getDimensions(): array
    {
        return $this->assetDirectory->getDimensions();
    }

    public function createVariantForContext($context)
    {
        throw MethodIsNotSupportedInAdapter::becauseItIsAReadonlyAdapter('createVariantForContext');
    }

    public function isAutoCreated(): bool
    {
        return false;
    }

    public function getOtherNodeVariants(): array
    {
        return [];
    }

    public function getCacheEntryIdentifier(): string
    {
        return $this->getContextPath();
    }

    public function isRoot(): bool
    {
        return false;
    }

    public function isTethered(): bool
    {
        return false;
    }

    public function getContentStreamIdentifier(): ContentStreamIdentifier
    {
        throw new NodeMethodIsUnsupported(
            'getContentStreamIdentifier is unsupported in the legacy Node API.',
            1542893545
        );
    }

    public function getNodeAggregateIdentifier(): NodeAggregateIdentifier
    {
        return NodeAggregateIdentifier::fromString($this->getIdentifier());
    }

    public function getNodeTypeName(): NodeTypeName
    {
        return NodeTypeName::fromString($this->getNodeType()->getName());
    }

    public function getNodeName(): ?NodeName
    {
        return NodeName::fromString($this->getName());
    }

    /* @phpstan-ignore-next-line */
    public function getOriginDimensionSpacePoint(): OriginDimensionSpacePoint
    {
        throw new NodeMethodIsUnsupported(
            'getDimensionSpacePoint is unsupported in the legacy Node API.',
            1542893558
        );
    }

    public function getDimensionSpacePoint(): DimensionSpacePoint
    {
        throw new NodeMethodIsUnsupported(
            'getOriginDimensionSpacePoint is unsupported in the legacy Node API.',
            1542893562
        );
    }

    public function findParentNode(): TraversableNodeInterface
    {
        /** @var TraversableNodeInterface $parent */
        $parent = $this->getParent();
        return $parent;
    }

    public function findNodePath(): NodePath
    {
        return NodePath::fromString($this->getPath());
    }

    public function findNamedChildNode(NodeName $nodeName): TraversableNodeInterface
    {
        /** @var ?TraversableNodeInterface $childNode */
        // It's safe to return the old NodeInterface as TraversableNodeInterface
        // as the base implementation "Node" (this class) implements both interfaces at the same time.
        $childNode = $this->getNode((string)$nodeName);
        if ($childNode === null) {
            throw new NodeException(sprintf('Child node named "%s" not found', $nodeName), 1543406006);
        }
        return $childNode;
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findChildNodes(
        NodeTypeConstraints $nodeTypeConstraints = null,
        int $limit = null,
        int $offset = null
    ): TraversableNodes {
        /** @noinspection PhpDeprecationInspection */
        $filter = $nodeTypeConstraints !== null ? $nodeTypeConstraints->asLegacyNodeTypeFilterString() : null;
        // It's safe to return the old NodeInterface as TraversableNodeInterface
        // as the base implementation "Node" (this class) implements both interfaces at the same time.
        return TraversableNodes::fromArray($this->getChildNodes($filter, $limit, $offset));
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findReferencedNodes(): TraversableNodes
    {
        return TraversableNodes::fromArray([]);
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     * @phpstan-ignore-next-line
     */
    public function findNamedReferencedNodes(PropertyName $edgeName): TraversableNodes
    {
        return TraversableNodes::fromArray([]);
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findReferencingNodes(): TraversableNodes
    {
        return TraversableNodes::fromArray([]);
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     * @phpstan-ignore-next-line
     */
    public function findNamedReferencingNodes(PropertyName $nodeName): TraversableNodes
    {
        return TraversableNodes::fromArray([]);
    }

    public function equals(TraversableNodeInterface $other): bool
    {
        if ($other instanceof NodeInterface) {
            return $this->getContextPath() === $other->getContextPath();
        }

        // if $other is not a Legacy NodeInterface, they are always different.
        return false;
    }
}
