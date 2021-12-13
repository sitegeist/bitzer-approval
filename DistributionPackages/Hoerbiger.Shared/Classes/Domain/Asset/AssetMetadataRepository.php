<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Doctrine\DBAL\Connection;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\ConnectionFactory;
use Neos\Media\Domain\Model\Document;

/**
 * The asset metadata domain repository
 *
 * @Flow\Scope("singleton")
 */
final class AssetMetadataRepository
{
    public const TABLE_NAME_METADATA = 'hoerbiger_shared_assetmetadata';

    private Connection $connection;

    public function __construct(ConnectionFactory $connectionFactory)
    {
        $this->connection = $connectionFactory->create();
    }

    public function findForAsset(Document $document): AssetMetadata
    {
        $query = 'SELECT * FROM `' . self::TABLE_NAME_METADATA . '`'
            . ' WHERE asset_identifier = "' . $document->getIdentifier() . '"';

        $record = $this->connection->fetchAssociative($query);

        //
        // It is possible for Hoerbiger\Shared\Infrastructure\AssetMetadataManager->whenAssetWasCreated
        // to not be executed, due to not fired signals. In that case inconsistencies are created
        // for a small number of documents, which won't have a metadata record.
        //
        // Since these inconsistencies are negligible, we fall back to the last modified date of the
        // affected documents, because in most cases, those will be equivalent to the assumed
        // publishing date.
        //
        return $record
            ? $this->createMetadataFromRecord($record)
            : new AssetMetadata(\DateTimeImmutable::createFromMutable($document->getLastModified()));
    }

    public function add(Document $document, AssetMetadata $metadata): void
    {
        $this->connection->insert(
            self::TABLE_NAME_METADATA,
            [
                'asset_identifier' => $document->getIdentifier(),
                'date_published' => $metadata->getDatePublished()->format('Y-m-d H:i:s')
            ]
        );
    }

    public function remove(Document $document): void
    {
        $this->connection->delete(
            self::TABLE_NAME_METADATA,
            [
                'asset_identifier' => $document->getIdentifier()
            ]
        );
    }

    public function setDatePublished(Document $asset, \DateTimeInterface $datePublished): void
    {
        $this->connection->update(
            self::TABLE_NAME_METADATA,
            [
                'date_published' => $datePublished->format('Y-m-d H:i:s')
            ],
            [
                'asset_identifier' => $asset->getIdentifier()
            ]
        );
    }

    /**
     * @param array<string,mixed> $record
     */
    private function createMetadataFromRecord(array $record): AssetMetadata
    {
        return new AssetMetadata(
            new \DateTimeImmutable($record['date_published'])
        );
    }
}
