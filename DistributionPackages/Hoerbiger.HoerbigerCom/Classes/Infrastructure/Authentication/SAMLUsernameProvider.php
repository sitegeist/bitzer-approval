<?php
namespace Hoerbiger\HoerbigerCom\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Doctrine\Common\Collections\ArrayCollection;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Authentication\Provider\AbstractProvider;
use Neos\Flow\Security\Authentication\TokenInterface;

/**
 * The SAML username authentication provider
 */
class SAMLUsernameProvider extends AbstractProvider
{
    /**
     * @Flow\Inject
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject
     * @var UserFactory
     */
    protected $userFactory;

    /**
     * @return array<string>
     */
    public function getTokenClassNames(): array
    {
        return [
            SAMLUsernameToken::class
        ];
    }

    public function authenticate(TokenInterface $authenticationToken): void
    {
        $authenticationToken->setAccount($this->getAccount($authenticationToken));
        $authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
    }

    private function getAccount(TokenInterface $authenticationToken): Account
    {
        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName(
            $authenticationToken->getCredentials()['identifier'],
            'Neos.Neos:Backend'
        );
        if ($account) {
            return $account;
        }

        $user = $this->userFactory->createFromAuthenticationToken($authenticationToken);
        /** @var ArrayCollection<int,Account> $accounts */
        $accounts = $user->getAccounts();
        /** @var Account $account */
        $account = $accounts->first();

        return $account;
    }
}
