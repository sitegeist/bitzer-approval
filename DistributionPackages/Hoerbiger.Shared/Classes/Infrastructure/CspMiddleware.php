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
                'www.hoerbiger.com', 'hoerbiger.com', 'www-prod.hoerbiger.com', 'vpro0994.proserver.punkt.de' => [
                    "'sha256-votYlhJgQQL7B5uCEicJFHHUkeq0oHcReTNP3Fqsuqk='",
                    "'sha256-DYcV0zWq4vQusrIN0layNTr7/PQ5qiMZvWBdy/YQkaY='"
                ],
                default => [
                    "'sha256-syzaMKYH4pbBEkODhTM5Fg82yrKuWyNCC8KOnsq9v50='",
                    "'sha256-DYcV0zWq4vQusrIN0layNTr7/PQ5qiMZvWBdy/YQkaY='"
                ]
            };
            $response = $response->withHeader(
                'Content-Security-Policy',
                implode(
                    '; ',
                    /** @codingStandardsIgnoreStart */
                    [
                        "connect-src 'self' *.hoerbiger.com hoerbiger.com *.googleapis.com *.cookiebot.com",
                        "default-src 'self' *.hoerbiger.com hoerbiger.com",
                        "font-src 'self' *.hoerbiger.com hoerbiger.com",
                        "frame-src 'self' https://consentcdn.cookiebot.com https://consent.cookiebot.com https://piwik.hoerbiger.com",
                        "img-src 'self' https: data: *.hoerbiger.com hoerbiger.com *.googleapis.com *.cookiebot.com *.gstatic.com",
                        "media-src 'self'",
                        "script-src 'self' " . implode(' ', $inlineScriptHashes) . " *.hoerbiger.com hoerbiger.com *.cookiebot.com *.googleapis.com *.recruitmentplatform.com",
                        "style-src 'self' 'unsafe-inline' *.hoerbiger.com *.cookiebot.com *.googleapis.com fast.fonts.net",
                    ]
                    /** @codingStandardsIgnoreEnd */
                )
            );
        }

        return $response;
    }
}
