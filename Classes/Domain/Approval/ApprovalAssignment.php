<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Bitzer\Domain\Agent\Agent;

/**
 * The approval assignment domain entity
 * @Flow\Proxy(false)
 */
final class ApprovalAssignment
{
    private string $workspaceName;

    private ?Workspace $workspaceToBeApproved;

    private string $responsibleAgentIdentifier;

    private ?Agent $responsibleAgent;

    public function __construct(
        string $workspaceName,
        ?Workspace $workspaceToBeApproved,
        string $responsibleAgentIdentifier,
        ?Agent $responsibleAgent
    ) {
        $this->workspaceName = $workspaceName;
        $this->workspaceToBeApproved = $workspaceToBeApproved;
        $this->responsibleAgentIdentifier = $responsibleAgentIdentifier;
        $this->responsibleAgent = $responsibleAgent;
    }

    public function getWorkspaceName(): string
    {
        return $this->workspaceName;
    }

    public function getWorkspaceToBeApproved(): ?Workspace
    {
        return $this->workspaceToBeApproved;
    }

    public function getResponsibleAgentIdentifier(): string
    {
        return $this->responsibleAgentIdentifier;
    }

    public function getResponsibleAgent(): ?Agent
    {
        return $this->responsibleAgent;
    }

    public function getIdentifier(): ApprovalAssignmentIdentifier
    {
        return new ApprovalAssignmentIdentifier(
            $this->workspaceName,
            $this->responsibleAgentIdentifier
        );
    }
}
