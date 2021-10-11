<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Doctrine\DBAL\Connection;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\ConnectionFactory;
use Sitegeist\Bitzer\Domain\Agent\AgentRepository;

/**
 * The approval assignment domain repository
 * @Flow\Scope("singleton")
 */
final class ApprovalAssignmentRepository
{
    const TABLE_NAME = 'sitegeist_bitzer_approval_domain_approval_approvalassignment';

    private WorkspaceRepository $workspaceRepository;

    private AgentRepository $agentRepository;

    private Connection $databaseConnection;

    public function __construct(
        WorkspaceRepository $workspaceRepository,
        AgentRepository $agentRepository,
        ConnectionFactory $connectionFactory
    ) {
        $this->workspaceRepository = $workspaceRepository;
        $this->agentRepository = $agentRepository;
        $this->databaseConnection = $connectionFactory->create();
    }

    public function findAll(): ApprovalAssignments
    {
        $query = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME
        );

        return new ApprovalAssignments(array_filter(array_map(
            function (array $tableRow): ?ApprovalAssignment {
                return $this->mapTableRowToApprovalAssignment($tableRow);
            },
            $query->fetchAllAssociative())
        ));
    }

    /**
     * @param array<string,string> $tableRow
     */
    private function mapTableRowToApprovalAssignment(array $tableRow): ?ApprovalAssignment
    {
        $workspace = $this->workspaceRepository->findByIdentifier($tableRow['workspace_name']);
        $agent = $this->agentRepository->findByString($tableRow['responsible_agent_identifier']);

        return ($workspace && $agent)
            ? new ApprovalAssignment($workspace, $agent)
            : null;
    }
}
