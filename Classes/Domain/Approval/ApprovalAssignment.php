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
    private Workspace $workspaceToBeApproved;

    private Agent $responsibleAgent;

    public function __construct(Workspace $workspaceToBeApproved, Agent $responsibleAgent)
    {
        $this->workspaceToBeApproved = $workspaceToBeApproved;
        $this->responsibleAgent = $responsibleAgent;
    }

    public function getWorkspaceToBeApproved(): Workspace
    {
        return $this->workspaceToBeApproved;
    }

    public function getResponsibleAgent(): Agent
    {
        return $this->responsibleAgent;
    }
}
