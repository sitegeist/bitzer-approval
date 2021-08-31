<?php
namespace Hoerbiger\HoerbigerCom\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\PersonName;

/**
 * The user factory
 * @Flow\Scope("singleton")
 */
class UserFactory
{
    /**
     * @Flow\Inject
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var PolicyService
     */
    protected $policyService;

    public function createFromAuthenticationToken(TokenInterface $authenticationToken): User
    {
        $account = new Account();
        $account->setAuthenticationProviderName('Neos.Neos:Backend');
        $account->setAccountIdentifier($authenticationToken->getCredentials()['identifier']);
        $account->setRoles([
            $this->policyService->getRole('Neos.Neos:Editor')
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

        $this->persistenceManager->persistAll();

        return $user;
    }
}
