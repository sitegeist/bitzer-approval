<?php
namespace Hoerbiger\HoerbigerCom\Tests\Unit\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Infrastructure\Authentication\SAMLUsernameProvider;
use Hoerbiger\HoerbigerCom\Infrastructure\Authentication\SAMLUsernameToken;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Tests\UnitTestCase;
use Neos\Utility\ObjectAccess;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the SAML username authentication provider
 */
class SAMLUsernameProviderTest extends UnitTestCase
{
    /**
     * @dataProvider actionRequestAndCredentialsProvider
     */
    public function testAuthenticate(SAMLUsernameToken $token, string $expectedAccountIdentifier): void
    {
        $subject = SAMLUsernameProvider::create('Neos.Neos:Backend', []);
        $mockAccountRepository = $this->createMock(AccountRepository::class);
        $dummyAccount = new Account();
        $dummyAccount->setAccountIdentifier($token->getCredentials()['identifier']);
        $mockAccountRepository->expects($this->any())
            ->method('findByAccountIdentifierAndAuthenticationProviderName')
            ->willReturn($dummyAccount);
        $this->inject($subject, 'accountRepository', $mockAccountRepository);

        $subject->authenticate($token);

        Assert::assertSame(TokenInterface::AUTHENTICATION_SUCCESSFUL, $token->getAuthenticationStatus());
        Assert::assertInstanceOf(Account::class, $token->getAccount());
        Assert::assertSame($expectedAccountIdentifier, $token->getAccount()->getAccountIdentifier());
    }

    public function actionRequestAndCredentialsProvider(): array
    {
        $token1 = new SAMLUsernameToken([]);
        ObjectAccess::setProperty($token1, 'credentials', ['identifier' => 'u1dd_pkf'], true);
        return [
            [
                $token1,
                'u1dd_pkf'
            ]
        ];
    }
}
