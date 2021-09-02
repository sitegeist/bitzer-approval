<?php
namespace Hoerbiger\HoerbigerCom\Infrastructure\Authentication;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Security\Authentication\Token\AbstractToken;

/**
 * The SAML username authentication token
 */
class SAMLUsernameToken extends AbstractToken
{
    public function updateCredentials(ActionRequest $actionRequest): void
    {
        $serverParams = $actionRequest->getHttpRequest()->getServerParams();
        $credentialsGiven = true;
        if (isset($serverParams['REMOTE_USER'])) {
            $this->credentials['identifier'] = $serverParams['REMOTE_USER'];
        } else {
            $credentialsGiven = false;
        }
        if (isset($serverParams['e-mail'])) {
            $this->credentials['email'] = $serverParams['e-mail'];
        } else {
            $credentialsGiven = false;
        }
        if (isset($serverParams['DISPLAY_NAME'])) {
            $this->credentials['name'] = $serverParams['DISPLAY_NAME'];
        } else {
            $credentialsGiven = false;
        }

        if ($credentialsGiven) {
            $this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
        }
    }
}
