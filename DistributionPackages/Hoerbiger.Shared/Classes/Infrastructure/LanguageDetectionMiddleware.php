<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Infrastructure;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use GuzzleHttp\Psr7\Response;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Detector;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A middleware that detects the user agent's language and decides what language version to redirect to
 */
class LanguageDetectionMiddleware implements MiddlewareInterface
{
    private Detector $localeDetector;

    private StreamFactoryInterface $contentFactory;

    private ContentDimensionPresetSourceInterface $contentDimensionPresetSource;

    public function __construct(
        Detector $localeDetector,
        StreamFactoryInterface $contentFactory,
        ContentDimensionPresetSourceInterface $contentDimensionPresetSource
    ) {
        $this->localeDetector = $localeDetector;
        $this->contentFactory = $contentFactory;
        $this->contentDimensionPresetSource = $contentDimensionPresetSource;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($request->getRequestTarget() !== '/') {
            // we only catch the root path
            return $response;
        }

        $browserLanguage = $this->localeDetector
            ->detectLocaleFromHttpHeader($_SERVER['HTTP_ACCEPT_LANGUAGE'])
            ->getLanguage();
        $languageSegment = $this->resolveLanguageUriPathSegment($browserLanguage);
        $targetUri = $request->getUri()->withPath('/' . $languageSegment . $request->getUri()->getPath());

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

    private function resolveLanguageUriPathSegment(string $browserLanguage): string
    {
        /** @var array<string,mixed> $languagePresets */
        $languagePresets = $this->contentDimensionPresetSource->getAllPresets()['language'];
        $matchingPreset = $languagePresets['presets'][$browserLanguage]
            ?? $languagePresets['presets'][$languagePresets['defaultPreset']];

        return $matchingPreset['uriSegment'];
    }
}
