<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class ContactRequestCommandHandler
{
    /**
     * @Flow\Inject
     * @var Messenger
     */
    protected $messenger;

    public function handleSendContactRequest(SendContactRequest $command): void
    {
        $this->messenger->sendProviderNotification($command);
        $this->messenger->sendCustomerConfirmationMessage($command);
    }
}
