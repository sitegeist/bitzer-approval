<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Application\Controller;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Domain\Contact\ContactRequestCommandHandler;
use Hoerbiger\HoerbigerCom\Domain\Contact\SendContactRequest;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;

/**
 * @Flow\Scope("singleton")
 */
final class ContactRequestController extends RestController
{
    /**
     * @Flow\Inject
     * @var ContactRequestCommandHandler
     */
    protected $contactRequestCommandHandler;

    /**
     * @var array<int,string>
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var array<string,class-string>
     */
    protected $viewFormatToObjectNameMap = ['json' => 'Neos\Flow\Mvc\View\JsonView'];

    public function sendAction(): void
    {
        $this->contactRequestCommandHandler->handleSendContactRequest(
            SendContactRequest::fromJsonString((string)$this->request->getHttpRequest()->getBody())
        );
    }
}
