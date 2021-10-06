<?php
namespace Hoerbiger\Shared\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use Neos\Neos\Utility\User as UserUtility;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\PersonName;

/**
 * The user factory
 * @Flow\Scope("singleton")
 */
final class UserFactory
{
    private AccountRepository $accountRepository;

    private UserRepository $userRepository;

    private PersistenceManagerInterface $persistenceManager;

    private PolicyService $policyService;

    private WorkspaceRepository $workspaceRepository;

    public function __construct(
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        PersistenceManagerInterface $persistenceManager,
        PolicyService $policyService,
        WorkspaceRepository $workspaceRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->persistenceManager = $persistenceManager;
        $this->policyService = $policyService;
        $this->workspaceRepository = $workspaceRepository;
    }

    public function createFromAuthenticationToken(TokenInterface $authenticationToken): User
    {
        $account = new Account();
        $account->setAuthenticationProviderName('Neos.Neos:Backend');
        $account->setAccountIdentifier($authenticationToken->getCredentials()['identifier']);
        $account->setRoles([
            $this->policyService->getRole('Neos.Neos:RestrictedEditor')
        ]);
        $this->accountRepository->add($account);

        $user = new User();
        $user->addAccount($account);

        $emailAddress = new ElectronicAddress();
        $emailAddress->setType('Email');
        $emailAddress->setUsage('Work');
        $emailAddress->setIdentifier($authenticationToken->getCredentials()['email']);
        $emailAddress->setApproved(true);
        $user->addElectronicAddress($emailAddress);

        $name = new PersonName();
        $name->setOtherName($authenticationToken->getCredentials()['name']);
        $user->setName($name);

        $this->userRepository->add($user);

        $workspace = new Workspace(
            UserUtility::getPersonalWorkspaceNameForUsername($account->getAccountIdentifier()),
            $this->workspaceRepository->findByIdentifier('review'),
            $user
        );
        $workspace->setDescription('Persönlicher Workspace von ' . $name);
        $this->workspaceRepository->add($workspace);

        $this->persistenceManager->persistAll();

        return $user;
    }
}
