<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MostWanted;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Doctrine\DBAL\Connection;
use Hoerbiger\HoerbigerCom\Domain\Navigation\NavigationNodeRepository;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\ConnectionFactory;

/**
 * The repository for wanted nodes
 *
 * @Flow\Scope("singleton")
 */
final class WantedNodeRepository
{
    const TABLE_NAME = 'hoerbiger_hoerbigercom_mostwanted_wantednode';

    private Connection $databaseConnection;

    private ContentContextFactory $contentContextFactory;

    private NavigationNodeRepository $navigationNodeRepository;

    public function __construct(
        ConnectionFactory $connectionFactory,
        ContentContextFactory $contentContextFactory,
        NavigationNodeRepository $navigationNodeRepository
    ) {
        $this->databaseConnection = $connectionFactory->create();
        $this->contentContextFactory = $contentContextFactory;
        $this->navigationNodeRepository = $navigationNodeRepository;
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findMostWanted(string $language, TraversableNodeInterface $site): TraversableNodes
    {
        $records = $this->databaseConnection->executeQuery(
            'SELECT node_aggregate_identifier FROM ' . self::TABLE_NAME . '
                WHERE language = :language
                AND node_aggregate_identifier IN (
                    SELECT DISTINCT identifier FROM neos_contentrepository_domain_model_nodedata
                )
                AND node_aggregate_identifier NOT IN (:excludedIdentifiers)
                ORDER BY count DESC
                LIMIT 5',
            [
                'language' => $language,
                'excludedIdentifiers' => $this->resolveExcludedIdentifiers($site)
            ],
            [
                'excludedIdentifiers' => Connection::PARAM_STR_ARRAY
            ]
        )->fetchAllAssociative();
        $subgraph = $this->contentContextFactory->create($language);

        return TraversableNodes::fromArray(array_filter(array_map(
            function (array $record) use ($subgraph): ?TraversableNodeInterface {
                /** @var ?TraversableNodeInterface $node */
                $node = $subgraph->getNodeByIdentifier($record['node_aggregate_identifier']);
                return $node;
            },
            $records
        )));
    }

    /**
     * @return array<int,string>
     */
    private function resolveExcludedIdentifiers(TraversableNodeInterface $site): array
    {
        $excludedIdentifiers = [(string)$site->getNodeAggregateIdentifier()];
        $primaryNavigationItems = $this->navigationNodeRepository->findMainNavigationDocuments($site);

        $mapper = function (TraversableNodes $nodes): array {
            return array_map(
                function (TraversableNodeInterface $node): string {
                    return (string)$node->getNodeAggregateIdentifier();
                },
                $nodes->toArray()
            );
        };

        foreach ($primaryNavigationItems as $primaryNavigationItem) {
            $excludedIdentifiers[] = (string)$primaryNavigationItem->getNodeAggregateIdentifier();
            $excludedIdentifiers = array_merge(
                $excludedIdentifiers,
                $mapper($this->navigationNodeRepository->findMainNavigationDocuments($primaryNavigationItem))
            );
        }

        return array_merge(
            $excludedIdentifiers,
            $mapper($this->navigationNodeRepository->findHeaderNavigationDocuments($site)),
            $mapper($this->navigationNodeRepository->findFooterNavigationDocuments($site))
        );
    }

    public function registerWantedNode(RegisterWantedNode $command): void
    {
        $this->databaseConnection->executeQuery(
            'INSERT INTO ' . self::TABLE_NAME . '
                SET language = :language, node_aggregate_identifier = :nodeAggregateIdentifier, count = 1
                ON DUPLICATE KEY UPDATE count=count+1',
            [
                'language' => $command->getLanguage(),
                'nodeAggregateIdentifier' => (string)$command->getNodeAggregateIdentifier()
            ]
        );
    }
}
