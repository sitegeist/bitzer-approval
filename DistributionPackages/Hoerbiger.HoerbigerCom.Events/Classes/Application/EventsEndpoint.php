<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Application;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Hoerbiger\HoerbigerCom\Events\Domain\Events;
use Hoerbiger\HoerbigerCom\Events\Domain\EventsQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventsEndpoint implements MiddlewareInterface
{
    public const URI_PATH = '/events/query';

    public function __construct(
        private readonly Events $events,
        private readonly LocalizationService $localizationService
    ) {
    }

    public static function getUri(): UriInterface
    {
        return ServerRequest::getUriFromGlobals()
            ->withQuery('')
            ->withFragment('')
            ->withPort(null)
            ->withPath(self::URI_PATH);
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if ($request->getUri()->getPath() === self::URI_PATH
            && isset($request->getQueryParams()['query'])
            && $request->getMethod() === 'GET'
        ) {
            $query = EventsQuery::fromJsonString($request->getQueryParams()['query']);

            $this->localizationService->getConfiguration()->setCurrentLocale(new Locale($query->locale));

            $queryResult = $this->events->findByQuery($query);

            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(
                    $queryResult,
                    JSON_THROW_ON_ERROR
                )
            );
        }

        return $handler->handle($request);
    }
}
