<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\Flow\Annotations as Flow;

/**
 * The approval assignment identifier domain value object
 * @Flow\Proxy(false)
 */
final class ApprovalAssignmentIdentifier
{
    private string $workspaceName;

    private string $responsibleAgentIdentifier;

    public function __construct(
        string $workspaceName,
        string $responsibleAgentIdentifier
    ) {
        $this->workspaceName = $workspaceName;
        $this->responsibleAgentIdentifier = $responsibleAgentIdentifier;
    }

    public static function fromString(string $string): self
    {
        list($agentIdentifier, $workspaceName) = explode('@', $string);

        return new self($workspaceName, $agentIdentifier);
    }

    public function getWorkspaceName(): string
    {
        return $this->workspaceName;
    }

    public function getResponsibleAgentIdentifier(): string
    {
        return $this->responsibleAgentIdentifier;
    }

    public function toString(): string
    {
        return $this->responsibleAgentIdentifier . '@' . $this->workspaceName;
    }

    /**
     * @todo move to presentation object
     */
    public function getSelector(): string
    {
        return \str_replace(['.', ':', '@'], '-', $this->toString());
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
