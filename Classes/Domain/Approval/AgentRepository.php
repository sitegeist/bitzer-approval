<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use Sitegeist\Bitzer\Domain\Agent\Agent;
use Sitegeist\Bitzer\Domain\Agent\Agents;

/**
 * The agent domain repository
 * @Flow\Scope("singleton")
 */
final class AgentRepository
{
    private UserRepository $userRepository;

    private PolicyService $policyService;

    private PersistenceManagerInterface $persistenceManager;

    public function __construct(
        UserRepository $userRepository,
        PolicyService $policyService,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->userRepository = $userRepository;
        $this->policyService = $policyService;
        $this->persistenceManager = $persistenceManager;
    }

    public function findApplicable(): Agents
    {
        $agents = [];

        $roles = $this->policyService->getRoles(false);
        $roles = array_filter($roles, function (Role $role): bool {
            $parentRoles = $role->getAllParentRoles();
            return isset($parentRoles['Neos.Neos:LivePublisher'])
                && isset($parentRoles['Sitegeist.Bitzer:Agent']);
        });

        foreach ($roles as $role) {
            $agents[] = Agent::fromRole($role);
        }

        foreach ($this->userRepository->findAll() as $user) {
            /** @var User $user */
            foreach ($user->getAccounts() as $account) {
                foreach ($roles as $role) {
                    if ($account->hasRole($role)) {
                        $agents[] = Agent::fromUser($user, $this->persistenceManager->getIdentifierByObject($user));
                        break 2;
                    }
                }
            }
        }

        return new Agents($agents);
    }
}
