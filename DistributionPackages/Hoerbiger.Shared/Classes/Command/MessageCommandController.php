<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\SwiftMailer\Message;

/**
 * A command controller for test messaging
 */
final class MessageCommandController extends CommandController
{
    public function sendTestCommand(string $recipient): void
    {
        $message = new Message(
            'hoerbiger.com test message',
            'This is a test message, please ignore it.',
            'text/html'
        );
        $message->setFrom('noreply@hoerbiger.com');
        $message->setTo($recipient);

        $message->send();
    }
}
