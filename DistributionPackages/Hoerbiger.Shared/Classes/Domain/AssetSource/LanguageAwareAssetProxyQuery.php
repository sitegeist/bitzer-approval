<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Neos\Flow\Persistence\QueryInterface;

final class LanguageAwareAssetProxyQuery implements AssetProxyQueryInterface
{
    private QueryInterface $flowPersistenceQuery;

    private LanguageAwareAssetSource $assetSource;

    public function __construct(QueryInterface $flowPersistenceQuery, LanguageAwareAssetSource $assetSource)
    {
        $this->flowPersistenceQuery = $flowPersistenceQuery;
        $this->assetSource = $assetSource;
    }

    public function setOffset(int $offset): void
    {
        $this->flowPersistenceQuery->setOffset($offset);
    }

    public function getOffset(): int
    {
        /** @phpstan-ignore-next-line */
        return $this->flowPersistenceQuery->getOffset();
    }

    public function setLimit(int $limit): void
    {
        $this->flowPersistenceQuery->setLimit($limit);
    }

    public function getLimit(): int
    {
        /** @phpstan-ignore-next-line */
        return $this->flowPersistenceQuery->getLimit();
    }

    public function execute(): LanguageAwareAssetProxyQueryResult
    {
        return new LanguageAwareAssetProxyQueryResult($this->flowPersistenceQuery->execute(), $this->assetSource);
    }

    public function setSearchTerm(string $searchTerm): void
    {
    }

    /**
     * @return string|void
     */
    public function getSearchTerm()
    {
    }

    public function count(): int
    {
        return $this->flowPersistenceQuery->count();
    }
}
