<?php
namespace Hoerbiger\Shared\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Doctrine\Common\Collections\ArrayCollection;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Authentication\Provider\AbstractProvider;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Security\Context as SecurityContext;

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
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

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
        if ($authenticationToken->getAuthenticationStatus() === TokenInterface::AUTHENTICATION_NEEDED) {
            $authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
            $authenticationToken->setAccount($this->getAccount($authenticationToken));
            $this->initializeCsrfProtectionToken();
        }
    }

    /**
     * This is usually done as a side effect in @see \Neos\Neos\Controller\LoginController::onAuthenticationSuccess()
     */
    private function initializeCsrfProtectionToken(): void
    {
        $this->securityContext->getCsrfProtectionToken();
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
