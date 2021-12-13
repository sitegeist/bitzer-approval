<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\AssetSource\LanguageAwareAssetSourceIdentifier;
use Neos\ContentRepository\Domain\Model\ArrayPropertyCollection;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\Query;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetVariantInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Repository\DocumentRepository;

/**
 * The asset collection adapter domain entity
 */
final class AssetCollectionAdapter extends AbstractMediaNodeAdapter
{
    const NODE_TYPE_NAME = 'Hoerbiger.Shared:Adapter.AssetCollection';

    private AssetCollection $assetCollection;

    private DocumentRepository $documentRepository;

    private NodeTypeManager $nodeTypeManager;

    private AssetAdapterFactory $assetAdapterFactory;

    /**
     * @Flow\Inject
     * @var ReflectionService
     */
    protected $reflectionService;

    /**
     * @Flow\Inject
     * @var NodeTypeConstraintFactory
     */
    protected $nodeTypeConstraintFactory;

    public function __construct(
        AssetCollection $assetCollection,
        string $identifier,
        AssetDirectory $assetDirectory,
        int $index,
        DocumentRepository $documentRepository,
        NodeTypeManager $nodeTypeManager,
        AssetAdapterFactory $assetAdapterFactory
    ) {
        $this->assetCollection = $assetCollection;
        $this->documentRepository = $documentRepository;
        $this->nodeTypeManager = $nodeTypeManager;
        $this->assetAdapterFactory = $assetAdapterFactory;
        $path = $assetDirectory->getPath() . '/' . $identifier;

        parent::__construct(
            $assetDirectory,
            $identifier,
            $path,
            $index,
            $this->nodeTypeManager->getNodeType(self::NODE_TYPE_NAME),
            new ArrayPropertyCollection([
                'name' => $assetCollection->getTitle(),
                'uriPathSegment' => strtolower($assetCollection->getTitle())
            ])
        );
    }

    public static function nodeTypeName(): NodeTypeName
    {
        return NodeTypeName::fromString(self::NODE_TYPE_NAME);
    }

    public function getLabel(): string
    {
        return $this->assetCollection->getTitle();
    }

    public function getParentPath(): string
    {
        return $this->assetDirectory->getPath();
    }

    public function getNode($path): ?AssetAdapter
    {
        /** @var ?Document $document */
        $document = $this->createQuery($path)->execute()->getFirst();

        return $document
            ? $this->assetAdapterFactory->create(
                $document,
                $this->assetDirectory,
                $this
            ): null;
    }

    public function getPrimaryChildNode(): ?AssetAdapter
    {
        /** @var ?Document $asset */
        $asset = $this->createQuery()->execute()->getFirst();

        return $asset
            ? $this->assetAdapterFactory->create(
                $asset,
                $this->assetDirectory,
                $this
            ) : null;
    }

    /**
     * @return array<int,AssetAdapter>
     */
    public function getChildNodes($nodeTypeFilter = null, $limit = null, $offset = null): array
    {
        if ($nodeTypeFilter) {
            $nodeTypeConstraints = $this->nodeTypeConstraintFactory->parseFilterString($nodeTypeFilter);
            if (!$nodeTypeConstraints->matches(AssetCollectionAdapter::nodeTypeName())) {
                return [];
            }
        }

        $assetAdapters = [];
        $index = 0;

        foreach ($this->createQuery()->execute() as $document) {
            /** @var Document $document */
            if ($adapter = $this->assetAdapterFactory->create(
                $document,
                $this->assetDirectory,
                $this,
                $index
            )) {
                $assetAdapters[] = $adapter;
            }
            $index += 128;
        }

        return $assetAdapters;
    }

    /**
     * @return TraversableNodes<int,AssetAdapter>
     */
    public function findChildNodes(
        NodeTypeConstraints $nodeTypeConstraints = null,
        int $limit = null,
        int $offset = null
    ): TraversableNodes {
        if (!$nodeTypeConstraints || $nodeTypeConstraints->matches(AssetAdapter::nodeTypeName())) {
            /** @var TraversableNodes<int,AssetAdapter> $childNodes */
            $childNodes = parent::findChildNodes($nodeTypeConstraints, $limit, $offset);
            return $childNodes;
        }

        return TraversableNodes::fromArray([]);
    }

    public function hasChildNodes($nodeTypeFilter = null): bool
    {
        $nodeTypeConstraints = $nodeTypeFilter
            ? $this->nodeTypeConstraintFactory->parseFilterString($nodeTypeFilter)
            : null;

        return $this->countChildNodes($nodeTypeConstraints) > 0;
    }

    public function getParent(): AssetDirectory
    {
        return $this->assetDirectory;
    }

    public function countChildNodes(?NodeTypeConstraints $nodeTypeConstraints = null): int
    {
        if (!$nodeTypeConstraints || $nodeTypeConstraints->matches(AssetAdapter::nodeTypeName())) {
            return $this->createQuery()->count();
        }
        return 0;
    }

    private function createQuery(string $assetIdentifier = null): QueryInterface
    {
        /** @var Query $query */
        $query = $this->documentRepository->createQuery();
        $constraints = [
            $query->equals(
                'assetSourceIdentifier',
                LanguageAwareAssetSourceIdentifier::fromDimensions($this->getContext()->getDimensions())->getValue()
            ),
            $query->contains('assetCollections', $this->assetCollection)
        ];
        if ($assetIdentifier) {
            $constraints[] = $query->equals('identifier', $assetIdentifier);
        }
        /** @var Query $query */
        $query = $query->matching($query->logicalAnd($constraints));

        $queryBuilder = $query->getQueryBuilder();

        $variantClassNames = $this->reflectionService->getAllImplementationClassNamesForInterface(
            AssetVariantInterface::class
        );
        foreach ($variantClassNames as $variantClassName) {
            $queryBuilder->andWhere('e NOT INSTANCE OF ' . $variantClassName);
        }

        return $query;
    }
}
