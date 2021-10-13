<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Doctrine\DBAL\Connection;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\ConnectionFactory;
use Sitegeist\Bitzer\Domain\Agent\Agent;
use Sitegeist\Bitzer\Domain\Agent\AgentIdentifier;
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
        $rows = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME
        )->fetchAllAssociative();

        return new ApprovalAssignments(array_map(
            function (array $tableRow): ?ApprovalAssignment {
                return $this->mapTableRowToApprovalAssignment($tableRow);
            },
            $rows
        ));
    }

    public function findByIdentifier(ApprovalAssignmentIdentifier $identifier): ?ApprovalAssignment
    {
        $row = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME . '
                WHERE workspace_name = :workspaceName
                AND responsible_agent_identifier = :responsibleAgentIdentifier',
            [
                'workspaceName' => $identifier->getWorkspaceName(),
                'responsibleAgentIdentifier' => $identifier->getResponsibleAgentIdentifier()
            ]
        )->fetchAssociative();

        return $row
            ? $this->mapTableRowToApprovalAssignment($row)
            : null;
    }

    public function findResponsibleAgentsForWorkspace(string $workspaceName): array
    {
        $rows = $this->databaseConnection->executeQuery(
            'SELECT * FROM ' . self::TABLE_NAME . '
                WHERE workspace_name = :workspaceName',
            [
                'workspaceName' => $workspaceName
            ]
        )->fetchAllAssociative();

        return array_filter(array_map(function (array $row): ?Agent {
            return $this->agentRepository->findByIdentifier(AgentIdentifier::fromString($row['responsible_agent_identifier']));
        }, $rows));
    }

    public function assign(array $assignmentData): void
    {
        $this->databaseConnection->insert(
            self::TABLE_NAME,
            [
                'workspace_name' => $assignmentData['workspaceName'],
                'responsible_agent_identifier' => $assignmentData['responsibleAgentIdentifier'],
            ]
        );
    }

    public function reassign(ApprovalAssignmentIdentifier $identifier, array $assignmentData): void
    {
        $this->databaseConnection->update(
            self::TABLE_NAME,
            [
                'responsible_agent_identifier' => $assignmentData['responsibleAgentIdentifier'],
            ],
            [
                'workspace_name' => $identifier->getWorkspaceName(),
                'responsible_agent_identifier' => $identifier->getResponsibleAgentIdentifier()
            ]
        );
    }

    public function removeAssignment(ApprovalAssignmentIdentifier $identifier): void
    {
        $this->databaseConnection->delete(
            self::TABLE_NAME,
            [
                'workspace_name' => $identifier->getWorkspaceName(),
                'responsible_agent_identifier' => $identifier->getResponsibleAgentIdentifier()
            ]
        );
    }

    /**
     * @param array<string,string> $tableRow
     */
    private function mapTableRowToApprovalAssignment(array $tableRow): ApprovalAssignment
    {
        $workspaceName = $tableRow['workspace_name'];
        $responsibleAgentIdentifier = AgentIdentifier::fromString($tableRow['responsible_agent_identifier']);

        return new ApprovalAssignment(
            $workspaceName,
            $this->workspaceRepository->findByIdentifier($workspaceName),
            $responsibleAgentIdentifier->toString(),
            $this->agentRepository->findByIdentifier($responsibleAgentIdentifier)
        );
    }
}
