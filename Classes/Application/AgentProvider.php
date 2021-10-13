<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Application;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Bitzer\Approval\Domain\Approval\AgentRepository;
use Sitegeist\Bitzer\Domain\Agent\Agents;

/**
 * The widget implementation
 * @Flow\Scope("singleton")
 */
final class AgentProvider implements ProtectedContextAwareInterface
{
    private AgentRepository $agentRepository;

    public function __construct(AgentRepository $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    public function getApprovalAgents(): Agents
    {
        return $this->agentRepository->findApplicable();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
