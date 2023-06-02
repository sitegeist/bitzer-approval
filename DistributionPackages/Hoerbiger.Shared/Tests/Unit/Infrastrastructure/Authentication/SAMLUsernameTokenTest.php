<?php
namespace Hoerbiger\HoerbigerCom\Tests\Unit\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Infrastructure\Authentication\SAMLUsernameToken;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Tests\UnitTestCase;
use PHPUnit\Framework\Assert;

/**
 * The SAML username authentication token
 */
class SAMLUsernameTokenTest extends UnitTestCase
{
    /**
     * @dataProvider actionRequestAndCredentialsProvider
     */
    public function testUpdateCredentials(ActionRequest $actionRequest, array $expectedCredentials): void
    {
        $subject = new SAMLUsernameToken();
        $subject->updateCredentials($actionRequest);
        Assert::assertSame($expectedCredentials, $subject->getCredentials());
    }

    public function actionRequestAndCredentialsProvider(): array
    {
        return [
            [
                ActionRequest::fromHttpRequest(new ServerRequest(
                    'GET',
                    new Uri('https://hoerbiger.com/neos'),
                    [],
                    null,
                    '1.1',
                    []
                )),
                []
            ],
            [
                ActionRequest::fromHttpRequest(new ServerRequest(
                    'GET',
                    new Uri('https://hoerbiger.com/neos'),
                    [],
                    null,
                    '1.1',
                    [
                        'DISPLAY_NAME' => 'Peter-Klaus Fledermaus',
                        'e-mail' => 'peter-klaus@fledermaus.de',
                        'REMOTE_USER' => 'u1dd_pkf'
                    ]
                )),
                [
                    'identifier' => 'u1dd_pkf',
                    'email' => 'peter-klaus@fledermaus.de',
                    'name' => 'Peter-Klaus Fledermaus'
                ]
            ]
        ];
    }
}
