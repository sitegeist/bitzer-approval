<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Application;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Response;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\MarketingUrlRepository;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Model\Domain;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\UriService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Flow\Scope("singleton")
 */
class MarketingUrlMiddleware implements MiddlewareInterface
{
    /**
     * @Flow\Inject
     * @var MarketingUrlRepository
     */
    protected $marketingUrlRepository;

    /**
     * @Flow\Inject
     * @var UriService
     */
    protected $uriService;

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionPresetSource;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $marketingUrl = $this->marketingUrlRepository->findByPath(trim($request->getUri()->getPath(), '/'));
        if ($marketingUrl) {
            $languagePresets = $this->contentDimensionPresetSource->getAllPresets()['language']['presets'];
            $preset = null;

            foreach (BrowserLanguages::fromHttpHeader(
                $request->getServerParams()['HTTP_ACCEPT_LANGUAGE']
            ) as $browserLocale) {
                if (isset($languagePresets[$browserLocale])) {
                    $preset = $languagePresets[$browserLocale];
                    break;
                }
            }

            $preset = $preset ?: $languagePresets['en'];
            $languageValues = $preset['values'];

            /** @var ContentContext $subgraph */
            $subgraph = $this->contentContextFactory->create([
                'dimensions' => ['language' => $languageValues],
                'targetDimensions' => ['language' => reset($languageValues)],
                'invisibleContentShown' => true,
                'inaccessibleContentShown' => true
            ]);

            $targetDocument = $subgraph->getNodeByIdentifier((string)$marketingUrl->getNodeAggregateIdentifier());

            if ($targetDocument instanceof Node) {
                $targetUri = $this->uriService->getNodeUri($targetDocument, true);
                $primaryDomain = $subgraph->getCurrentSite()->getPrimaryDomain();
                if ($primaryDomain instanceof Domain) {
                    $targetUri = $targetUri->withHost($primaryDomain->getHostname());
                }
                return new Response(307, [
                    'Location' => (string)$targetUri
                ]);
            }
        }

        return $handler->handle($request);
    }
}
