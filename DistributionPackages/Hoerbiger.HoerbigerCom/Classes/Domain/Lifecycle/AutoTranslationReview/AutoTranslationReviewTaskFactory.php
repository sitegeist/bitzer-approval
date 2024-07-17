<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Neos\Service\UserService;
use Psr\Http\Message\UriInterface;
use Sitegeist\Bitzer\Domain\Object\ObjectRepository;
use Sitegeist\Bitzer\Domain\Task\ActionStatusType;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Domain\Task\TaskClassName;
use Sitegeist\Bitzer\Domain\Task\TaskFactoryInterface;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;
use Sitegeist\Bitzer\Domain\Task\TaskInterface;
use Sitegeist\Bitzer\Domain\Agent\Agent;

/**
 * The auto-translation review task factory
 *
 * Creates review task objects with proper targets
 */
#[Flow\Scope('singleton')]
class AutoTranslationReviewTaskFactory implements TaskFactoryInterface
{
    public function __construct(
        private readonly ObjectRepository $objectRepository,
        private readonly UserService $userService,
    ) {
    }

    /**
     * @param array<string,mixed> $properties
     */
    final public function createFromRawData(
        TaskIdentifier $identifier,
        TaskClassName $className,
        array $properties,
        \DateTimeImmutable $scheduledTime,
        ActionStatusType $actionStatus,
        Agent $agent,
        ?NodeAddress $object,
        ?UriInterface $target
    ): TaskInterface {
        $node = null;
        if ($object) {
            $userWorkspaceName = $this->userService->getPersonalWorkspaceName();
            if ($userWorkspaceName) {
                $object = $object->withWorkspaceName($userWorkspaceName);
            }
            $node = $this->objectRepository->findByAddress($object);
        }

        return new AutoTranslationReviewTask(
            $identifier,
            $properties,
            $scheduledTime,
            $actionStatus,
            $agent,
            $node,
            $node ? $this->buildBackendUri($node) : null
        );
    }

    private function buildBackendUri(TraversableNodeInterface $object): Uri
    {
        $httpRequest = ServerRequest::fromGlobals();
        $actionRequest = ActionRequest::fromHttpRequest($httpRequest);
        $uriBuilder = new UriBuilder();
        $uriBuilder->setRequest($actionRequest);
        $uriBuilder->setCreateAbsoluteUri(true);

        return new Uri($uriBuilder->uriFor(
            'index',
            ['node' => $object],
            'Backend',
            'Neos.Neos.Ui'
        ));
    }
}
