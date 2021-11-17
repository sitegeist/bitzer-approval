<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Model\ArrayPropertyCollection;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;

/**
 * The asset adapter domain entity
 *
 * @Flow\Proxy(false)
 */
final class AssetAdapter extends AbstractMediaNodeAdapter
{
    const NODE_TYPE_NAME = 'Hoerbiger.Shared:Adapter.Asset';

    private AssetCollectionAdapter$assetCollectionAdapter;

    private Document $asset;

    public function __construct(
        Document $asset,
        CreativeWork $creativeWork,
        AssetCollectionAdapter $assetCollectionAdapter,
        AssetDirectory $assetDirectory,
        int $index,
        NodeType $nodeType
    ) {
        $this->assetCollectionAdapter = $assetCollectionAdapter;
        $this->asset = $asset;
        $path = $assetCollectionAdapter->getPath() . '/' . $asset->getIdentifier();

        parent::__construct(
            $assetDirectory,
            $asset->getIdentifier(),
            $path,
            $index,
            $nodeType,
            new ArrayPropertyCollection([
                'name' => $creativeWork->getName(),
                'title' => $creativeWork->getName(),
                'uriPathSegment' => strtolower($creativeWork->getName()),
                'description' => $creativeWork->getDescription(),
                'abstract' => $creativeWork->getAbstract(),
                'text' => $creativeWork->getText(),
                'copyrightHolder' => $creativeWork->getCopyrightHolder(),
                'dateModified' => $creativeWork->getDateModified(),
                'datePublished' => $creativeWork->getDatePublished(),
                'keywords' => $creativeWork->getKeywords(),
                'headline' => $creativeWork->getHeadline()
            ])
        );
    }

    public static function nodeTypeName(): NodeTypeName
    {
        return NodeTypeName::fromString(self::NODE_TYPE_NAME);
    }

    public function getAsset(): Document
    {
        return $this->asset;
    }

    public function getLabel(): string
    {
        return $this->asset->getLabel();
    }

    public function getParent()
    {
        return $this->assetCollectionAdapter;
    }

    public function getParentPath(): string
    {
        return $this->assetCollectionAdapter->getPath();
    }

    public function getNode($path)
    {
        /** @phpstan-ignore-next-line */
        return null;
    }

    public function getPrimaryChildNode()
    {
        /** @phpstan-ignore-next-line */
        return null;
    }

    public function getChildNodes($nodeTypeFilter = null, $limit = null, $offset = null): array
    {
        return [];
    }

    public function hasChildNodes($nodeTypeFilter = null): bool
    {
        return false;
    }

    public function countChildNodes(NodeTypeConstraints $nodeTypeConstraints = null): int
    {
        return 0;
    }

    public function isHidden(): bool
    {
        return false;
    }

    public function isVisible(): bool
    {
        return true;
    }
}
