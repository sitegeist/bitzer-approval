<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CspMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (!\str_starts_with($request->getUri()->getPath(), '/neos')) {
            $inlineScriptHashes = match ($request->getUri()->getHost()) {
                'www-dev.hoerbiger.com', 'www-stage.hoerbiger.com', 'www.hoerbiger.com', 'hoerbiger.com',
                'www-prod.hoerbiger.com', 'vpro0994.proserver.punkt.de' => [
                    "'sha256-votYlhJgQQL7B5uCEicJFHHUkeq0oHcReTNP3Fqsuqk='",
                    "'sha256-DYcV0zWq4vQusrIN0layNTr7/PQ5qiMZvWBdy/YQkaY='",
                    "'sha256-tE5FqWv+yqmPEClXs60CkxqJl/7p7o0wB46nNZm/aVY='"
                ],
                default => [
                    "'sha256-syzaMKYH4pbBEkODhTM5Fg82yrKuWyNCC8KOnsq9v50='",
                    "'sha256-DYcV0zWq4vQusrIN0layNTr7/PQ5qiMZvWBdy/YQkaY='",
                    "'sha256-tE5FqWv+yqmPEClXs60CkxqJl/7p7o0wB46nNZm/aVY='"
                ]
            };
        }

        return $response;
    }
}
