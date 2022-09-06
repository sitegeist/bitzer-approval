<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MarketingUrl;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Doctrine\DBAL\Connection;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\ConnectionFactory;

/**
 * The repository for marketing URLs
 *
 * @Flow\Scope("singleton")
 */
final class MarketingUrlRepository
{
    const TABLE_NAME = 'hoerbiger_hoerbigercom_marketingurl';

    private Connection $databaseConnection;

    public function __construct(
        ConnectionFactory $connectionFactory
    ) {
        $this->databaseConnection = $connectionFactory->create();
    }

    public function findAll(): MarketingUrls
    {
        $records = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME
        )->fetchAllAssociative();

        return new MarketingUrls(...array_map(
            fn (array $record): MarketingUrl => $this->mapRecordToMarketingUrl($record),
            $records
        ));
    }

    public function findByPath(string $path): ?MarketingUrl
    {
        $record = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME . '
            WHERE path = :path',
            [
                'path' => $path
            ]
        )->fetchAssociative();

        return $record
            ? $this->mapRecordToMarketingUrl($record)
            : null;
    }

    /**
     * @param array<string,string> $record
     */
    private function mapRecordToMarketingUrl(array $record): MarketingUrl
    {
        return new MarketingUrl(
            $record['path'],
            NodeAggregateIdentifier::fromString($record['node_aggregate_identifier'])
        );
    }

    /**
     * @throws PathIsAlreadyOccupied
     */
    public function handleRegisterMarketingUrl(RegisterMarketingUrl $command): void
    {
        if ($this->findByPath($command->getPath()) instanceof MarketingUrl) {
            throw PathIsAlreadyOccupied::create($command->getPath());
        }

        $this->databaseConnection->executeStatement(
            'INSERT INTO ' . self::TABLE_NAME . ' VALUES(:path, :nodeAggregateIdentifier)',
            [
                'path' => $command->getPath(),
                'nodeAggregateIdentifier' => (string)$command->getNodeAggregateIdentifier()
            ]
        );
    }

    /**
     * @throws PathIsNotYetOccupied
     */
    public function handleUpdateMarketingUrl(UpdateMarketingUrl $command): void
    {
        if (!$this->findByPath($command->getPath()) instanceof MarketingUrl) {
            throw PathIsNotYetOccupied::create($command->getPath());
        }

        $this->databaseConnection->executeStatement(
            'UPDATE ' . self::TABLE_NAME . '
                SET node_aggregate_identifier = :nodeAggregateIdentifier
                WHERE path = :path',
            [
                'path' => $command->getPath(),
                'nodeAggregateIdentifier' => (string)$command->getNodeAggregateIdentifier()
            ]
        );
    }

    /**
     * @throws PathIsNotYetOccupied
     */
    public function handleDeleteMarketingUrlUrl(DeleteMarketingUrl $command): void
    {
        if (!$this->findByPath($command->getPath()) instanceof MarketingUrl) {
            throw PathIsNotYetOccupied::create($command->getPath());
        }

        $this->databaseConnection->executeStatement(
            'DELETE FROM ' . self::TABLE_NAME . '
                WHERE path = :path',
            [
                'path' => $command->getPath()
            ]
        );
    }
}
