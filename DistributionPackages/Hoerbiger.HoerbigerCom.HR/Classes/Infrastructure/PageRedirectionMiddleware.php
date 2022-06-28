<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\HR\Infrastructure;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom.HR" package.
 **/

use GuzzleHttp\Psr7\Response;
use Neos\Flow\Annotations as Flow;
use Neos\Fusion\Core\Cache\ContentCache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A middleware that changes the path of the job pages
 */
class PageRedirectionMiddleware implements MiddlewareInterface
{
    /**
     * @Flow\InjectConfiguration(path="redirects")
     * @var array<string,string>
     */
    protected $redirects;

    /**
     * @var ContentCache
     * @Flow\Inject
     */
    protected $contentCache;

    private StreamFactoryInterface $contentFactory;

    public function __construct(StreamFactoryInterface $contentFactory)
    {
        $this->contentFactory = $contentFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $currentPath = $request->getUri()->getPath();

        $newPath = $this->redirects[$currentPath] ?? null;

        if (!$newPath) {
            return $handler->handle($request);
        }

        $this->contentCache->flushByTag('NodeType_' . 'Hoerbiger.HoerbigerCom.HR:Document.Job');

        $targetUri = $request->getUri()->withPath($newPath);

        $redirectResponse = new Response();
        $redirectBodyContent = sprintf(
            '<html><head><meta http-equiv="refresh" content="0;url=%s"/></head></html>',
            htmlentities((string)$targetUri, ENT_QUOTES, 'utf-8')
        );

        return $redirectResponse
            ->withStatus(307)
            ->withBody($this->contentFactory->createStream($redirectBodyContent))
            ->withHeader('Location', (string)$targetUri);
    }
}
