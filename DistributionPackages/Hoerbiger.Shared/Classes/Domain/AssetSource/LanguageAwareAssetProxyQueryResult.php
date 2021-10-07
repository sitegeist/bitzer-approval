<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Neos\Flow\Annotations\Proxy;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Media\Domain\Model\AssetInterface;

/**
 * @Proxy(false)
 */
final class LanguageAwareAssetProxyQueryResult implements AssetProxyQueryResultInterface
{
    private LanguageAwareAssetSource $assetSource;

    /** @var QueryResultInterface<int,LanguageAwareAssetProxy> */
    private QueryResultInterface $flowPersistenceQueryResult;

    private ?LanguageAwareAssetProxyQuery $query = null;

    /**
     * @param QueryResultInterface<int,LanguageAwareAssetProxy> $flowPersistenceQueryResult
     */
    public function __construct(
        QueryResultInterface $flowPersistenceQueryResult,
        LanguageAwareAssetSource $assetSource
    ) {
        $this->flowPersistenceQueryResult = $flowPersistenceQueryResult;
        $this->assetSource = $assetSource;
    }

    public function getQuery(): AssetProxyQueryInterface
    {
        if ($this->query === null) {
            $this->query = new LanguageAwareAssetProxyQuery(
                $this->flowPersistenceQueryResult->getQuery(),
                $this->assetSource
            );
        }
        return $this->query;
    }

    public function getFirst(): ?AssetProxyInterface
    {
        $asset = $this->flowPersistenceQueryResult->getFirst();
        if ($asset instanceof AssetInterface) {
            return new LanguageAwareAssetProxy($asset, $this->assetSource);
        } else {
            return null;
        }
    }

    /**
     * @return array<int,LanguageAwareAssetProxy>
     */
    public function toArray(): array
    {
        return array_map(function (AssetInterface $asset): LanguageAwareAssetProxy {
            return new LanguageAwareAssetProxy($asset, $this->assetSource);
        }, $this->flowPersistenceQueryResult->toArray());
    }

    public function current(): ?AssetProxyInterface
    {
        $asset = $this->flowPersistenceQueryResult->current();
        if ($asset instanceof AssetInterface) {
            return new LanguageAwareAssetProxy($asset, $this->assetSource);
        } else {
            return null;
        }
    }

    public function next()
    {
        $this->flowPersistenceQueryResult->next();
    }

    /**
     * @return bool|float|int|string
     */
    public function key()
    {
        return $this->flowPersistenceQueryResult->key();
    }

    public function valid(): bool
    {
        return $this->flowPersistenceQueryResult->valid();
    }

    public function rewind(): void
    {
        $this->flowPersistenceQueryResult->rewind();
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->flowPersistenceQueryResult->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     */
    public function offsetGet($offset): ?AssetProxyInterface
    {
        return new LanguageAwareAssetProxy(
            $this->flowPersistenceQueryResult->offsetGet($offset),
            $this->assetSource
        );
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Unsupported operation: ' . __METHOD__, 1510060444);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Unsupported operation: ' . __METHOD__, 1510060467);
    }

    public function count(): int
    {
        return $this->flowPersistenceQueryResult->count();
    }
}
