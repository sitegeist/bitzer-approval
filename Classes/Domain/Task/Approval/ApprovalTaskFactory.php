<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Task\Approval;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Psr\Http\Message\UriInterface;
use Sitegeist\Bitzer\Domain\Object\ObjectRepository;
use Sitegeist\Bitzer\Domain\Task\ActionStatusType;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Domain\Task\TaskClassName;
use Sitegeist\Bitzer\Domain\Task\TaskFactoryInterface;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;
use Sitegeist\Bitzer\Domain\Agent\Agent;

/**
 * The approval task factory
 * Creates approval task objects with proper targets
 *
 * @Flow\Scope("singleton")
 */
final class ApprovalTaskFactory implements TaskFactoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;
    }

    final public function createFromRawData(
        TaskIdentifier $identifier,
        TaskClassName $className,
        array $properties,
        \DateTimeImmutable $scheduledTime,
        ActionStatusType $actionStatus,
        Agent $agent,
        ?NodeAddress $object,
        ?UriInterface $target
    ): ApprovalTask {
        $resolvedObject = $object
            ? $this->objectRepository->findByAddress($object)
            : null;
        $target = $resolvedObject
            ? $this->buildBackendUri($resolvedObject)
            : null;

        return new ApprovalTask(
            $identifier,
            $properties,
            $scheduledTime,
            $actionStatus,
            $agent,
            $resolvedObject,
            $target
        );
    }

    private function buildBackendUri(TraversableNodeInterface $object): Uri
    {
        /** @var NodeInterface $object */
        $httpRequest = ServerRequest::fromGlobals();
        $actionRequest = ActionRequest::fromHttpRequest($httpRequest);
        $uriBuilder = new UriBuilder();
        $uriBuilder->setRequest($actionRequest);
        $uriBuilder->setCreateAbsoluteUri(true);

        return new Uri($uriBuilder->uriFor(
            'index',
            [
                'module' => 'management/workspaces',
                'moduleArguments' => [
                    '@package' => 'Neos.Neos',
                    '@controller' => 'Module\Management\Workspaces',
                    '@action' => 'show',
                    '@format' => 'html',
                    'workspace' => [
                        '__identity' => $object->getWorkspace()->getName()
                    ]
                ]
            ],
            'Backend\Module',
            'Neos.Neos'
        ));
    }
}
