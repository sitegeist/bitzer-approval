<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Application\Controller;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Hoerbiger\HoerbigerCom\Domain\MostWanted\NodeCannotBeRegisteredAsWanted;
use Hoerbiger\HoerbigerCom\Domain\MostWanted\RegisterWantedNode;
use Hoerbiger\HoerbigerCom\Domain\MostWanted\WantedNodeCommandHandler;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;

/**
 * The API endpoint for wanted nodes
 *
 * @Flow\Scope("singleton")
 */
final class WantedNodeController extends RestController
{
    /**
     * @var array<int,string>
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var array<string,class-string>
     */
    protected $viewFormatToObjectNameMap = ['json' => 'Neos\Flow\Mvc\View\JsonView'];

    private WantedNodeCommandHandler $commandHandler;

    public function __construct(WantedNodeCommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    public function registerAction(string $command): void
    {
        try {
            $this->commandHandler->handleRegisterWantedNode(RegisterWantedNode::fromJsonString($command));
        } catch (NodeCannotBeRegisteredAsWanted $exception) {
            $this->response->setStatusCode(400);
        }
    }
}
