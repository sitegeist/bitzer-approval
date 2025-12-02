<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequestFactory;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Neos\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The HTTP middleware to redirect company editors to their proper site node variant
 */
final class LanguageEditorZookeeper implements MiddlewareInterface
{
    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionPresetSource;

    public function __construct(
        private readonly UserService $userService,
        private readonly ContentContextFactory $contentContextFactory
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // redirect language bases user to the corresponding dimension
        if ($request->getUri()->getPath() === '/neos'
            || $request->getUri()->getPath() === '/neos/'
            || $request->getUri()->getPath() === '/neos/content' && $request->getQueryParams() === []
        ) {
            $backendUser = $this->userService->getBackendUser();

            $roleIds = LanguageBasedRoleIds::tryFromUser($backendUser);

            if ($roleIds) {
                $this->redirectToNode($this->resolveNodeToRedirectTo($roleIds->getFirst()), $request);
            }
        }

        return $handler->handle($request);
    }

    private function resolveNodeToRedirectTo(LanguageBasedRoleId $roleId): Node
    {
        $languageDimensionValue = $roleId->toContentDimensionValue($this->contentDimensionPresetSource);

        /** @var ContentContext $subgraph */
        $subgraph = $this->contentContextFactory->create([
            'workspaceName' => $this->userService->getPersonalWorkspaceName(),
            'dimensions' => [
                'language' => [$languageDimensionValue]
            ],
            'targetDimensions' => ['language' => $languageDimensionValue],
            'invisibleContentShown' => true,
            'inaccessibleContentShown' => true
        ]);

        /** @var Node $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();

        return $siteNode;
    }

    private function redirectToNode(Node $node, ServerRequestInterface $request): void
    {
        $uriBuilder = (new UriBuilder())
            ->reset()
            ->setFormat('html')
            ->setCreateAbsoluteUri(true);
        $uriBuilder->setRequest((new ActionRequestFactory())->createActionRequest($request));
        $nodeUri = $uriBuilder->uriFor('index', ['node' => $node], 'Backend', 'Neos.Neos.UI');

        ob_clean();
        header('Location: ' . $nodeUri, true, 307);
        exit();
    }
}
