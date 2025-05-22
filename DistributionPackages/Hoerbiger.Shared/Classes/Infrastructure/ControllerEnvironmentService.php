<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\BaseUriProvider;
use Neos\Flow\Http\ServerRequestAttributes;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Mvc\Routing\Dto\RouteParameters;
use Neos\Flow\ResourceManagement\CollectionInterface;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Neos\Domain\Model\Site;
use Neos\Flow\Mvc;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Utility\ObjectAccess;
use Sitegeist\MagicWand\ResourceManagement\ProxyAwareFileSystemSymlinkTarget;

#[Flow\Scope('singleton')]
final class ControllerEnvironmentService
{
    /**
     * @Flow\Inject(lazy=false)
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @Flow\Inject(lazy=false)
     * @var BaseUriProvider
     */
    protected $baseUriProvider;

    protected ControllerContext $controllerContext;

    public function __construct(
        private readonly SiteRepository $siteRepository,
    ) {
        $this->controllerContext = $this->createControllerContext();
    }

    public function getControllerContext(): ControllerContext
    {
        return $this->controllerContext;
    }

    public function createControllerContext(
        string $siteNodeName = 'hoerbiger-hoerbigercom'
    ): Mvc\Controller\ControllerContext {
        /** @var Site $site */
        $site = $this->siteRepository->findOneByNodeName($siteNodeName);
        $request = ServerRequest::fromGlobals();
        $uri = new Uri('https://' . $site->getPrimaryDomain()->getHostname() . '/');
        $request = $request->withUri($uri);
        $parameters = $request->getAttribute(
            ServerRequestAttributes::ROUTING_PARAMETERS
        ) ?? RouteParameters::createEmpty();
        $request = $request->withAttribute(
            ServerRequestAttributes::ROUTING_PARAMETERS,
            $parameters->withParameter(
                'requestUriHost',
                $request->getUri()->getHost()
            )
        );
        $actionRequest = Mvc\ActionRequest::fromHttpRequest($request);
        $uriBuilder = new Mvc\Routing\UriBuilder();
        $uriBuilder->setRequest($actionRequest);
        $uriBuilder->setCreateAbsoluteUri(true);

        return new Mvc\Controller\ControllerContext(
            $actionRequest,
            new Mvc\ActionResponse(),
            new Mvc\Controller\Arguments(),
            $uriBuilder
        );
    }

    public function setupHttpEnvironment(Mvc\Controller\ControllerContext $controllerContext): void
    {
        putenv('FLOW_REWRITEURLS=1');
        ObjectAccess::setProperty(
            $this->baseUriProvider,
            'configuredBaseUri',
            $controllerContext->getRequest()->getHttpRequest()->getUri(),
            true
        );

        foreach ($this->resourceManager->getCollections() as $collection) {
            /** @var CollectionInterface $collection */
            if ($collection->getTarget() instanceof ProxyAwareFileSystemSymlinkTarget) {
                $target = $collection->getTarget();
                ObjectAccess::setProperty($target, 'uriBuilder', $controllerContext->getUriBuilder(), true);
            }
        }
    }
}
