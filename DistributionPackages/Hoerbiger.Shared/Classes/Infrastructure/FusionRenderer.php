<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\ServerRequest;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Http;
use Neos\Flow\Mvc;
use Neos\Fusion\Core\Runtime as FusionRuntime;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\FusionService;

/**
 * @Flow\Scope("singleton")
 */
class FusionRenderer
{
    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var FusionService
     */
    protected $fusionService;

    /**
     * @var FusionRuntime
     */
    protected $fusionRuntime;

    public function initializeObject(): void
    {
        /** @var ContentContext $contentContext */
        $contentContext = $this->contentContextFactory->create([]);

        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $contentContext->getCurrentSiteNode();


        $requestHandler = $this->bootstrap->getActiveRequestHandler();
        if ($requestHandler instanceof Http\RequestHandler) {
            $request = $requestHandler->getHttpRequest();
        } else {
            $request = ServerRequest::fromGlobals();
        }
        $actionRequest = Mvc\ActionRequest::fromHttpRequest($request);
        $uriBuilder = new Mvc\Routing\UriBuilder();
        $uriBuilder->setRequest($actionRequest);

        $controllerContext = new Mvc\Controller\ControllerContext(
            $actionRequest,
            new Mvc\ActionResponse(),
            new Mvc\Controller\Arguments(),
            $uriBuilder
        );

        $this->fusionRuntime = $this->fusionService->createRuntime(
            $siteNode,
            $controllerContext
        );
    }

    /**
     * @param array<mixed> $contextVariables
     */
    public function render(
        string $fusionPath,
        array $contextVariables = []
    ): string {
        $this->fusionRuntime->pushContextArray($contextVariables);
        $result = $this->fusionRuntime->render($fusionPath);
        $this->fusionRuntime->popContext();

        return $result ?? '';
    }
}
