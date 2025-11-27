<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Flowpack\ElasticSearch\ContentRepositoryAdaptor\ElasticSearchClient;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Infrastructure\FusionRenderer;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Repository\DocumentRepository;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Scope("singleton")
 */
final class Stuff
{
    private ElasticSearchClient $searchClient;

    private StuffContentContextFactory $contentContextFactory;

    private DocumentRepository $documentRepository;

    private FusionRenderer $fusionRenderer;

    private CardFactory $cardFactory;

    private LinkFactory $linkFactory;

    public function __construct(
        ElasticSearchClient $searchClient,
        StuffContentContextFactory $contentContextFactory,
        DocumentRepository $documentRepository,
        FusionRenderer $fusionRenderer,
        CardFactory $cardFactory,
        LinkFactory $linkFactory
    ) {
        $this->searchClient = $searchClient;
        $this->contentContextFactory = $contentContextFactory;
        $this->documentRepository = $documentRepository;
        $this->fusionRenderer = $fusionRenderer;
        $this->cardFactory = $cardFactory;
        $this->linkFactory = $linkFactory;
    }

    public function findThingsByQuery(StuffQuery $query): StuffResponse
    {
        $contentContext = $this->contentContextFactory->createContentContext($query->getLanguage());
        $siteNode = $contentContext->getCurrentSiteNode();
        $this->searchClient->setContextNode($siteNode);
        /** @var TraversableNodeInterface $siteNode */
        $request = StuffRequest::create($siteNode);
        $request = $request->withPage($query->getPage()->value);
        $period = $query->getPeriod();
        if ($period) {
            $request = $request->withPeriod($period);
        }
        $request = $request->withSortingProperty($query->getSortingProperty());
        $searchTerm = $query->getSearchTerm();
        if ($searchTerm) {
            $request = $request->withSearchTerm($searchTerm);
            $request = $request->withMinimumScore(0.1);
        }
        $requestBeforeActiveFilters = clone $request;
        if (!$query->getTypes()->isEmpty()) {
            $request = $request->withTypes($query->getTypes());
        }
        $request = $request->withTypeFacet($requestBeforeActiveFilters->getFilters());

        $result = $this->searchClient->getIndex()
            ->request('GET', '/_search', [], json_encode($request) ?: null)
            ->getTreatedContent();

        $things = [];
        foreach ($result['hits']['hits'] as $searchHit) {
            $thing = $this->createThing($searchHit, $contentContext);
            if ($thing) {
                $things[] = $thing;
            }
        }

        $totalNumberOfHits = $result['hits']['total']['value'] ?? 0;

        return new StuffResponse(
            (int)($totalNumberOfHits / StuffRequest::DEFAULT_LIMIT),
            new Things(...$things),
            $totalNumberOfHits,
            TypeOptions::fromAggregations($result['aggregations'])
        );
    }

    /**
     * @param array<string,mixed> $searchHit
     */
    private function createThing(array $searchHit, ContentContext $contentContext): ?Thing
    {
        $highlight = null;
        if (isset($searchHit['highlight']) && is_array($searchHit['highlight']) && !empty($searchHit['highlight'])) {
            foreach ($searchHit['highlight'] as $highlightsByProperty) {
                $highlight = reset($highlightsByProperty);
                if (!empty($highlight)) {
                    if (\mb_substr($highlight, -1) !== '.') {
                        $highlight .= '...';
                    }
                    break;
                }
            }
        }
        switch ($searchHit['_source']['neos_type']) {
            case 'Hoerbiger.Shared:Adapter.Asset':
                $document = $this->documentRepository->findByIdentifier($searchHit['_source']['neos_node_identifier']);
                if (!$document instanceof Document) {
                    return null;
                }
                /** @var SlotInterface $card */
                $card = $this->cardFactory->forDocument(
                    $document,
                    $searchHit['_source']['searchType'],
                    $highlight
                );
                $link = $this->linkFactory
                    ->forAsset($document)
                    ->asContainer()
                    ->asBlankTargetLink()
                    ->withContent($card);
                break;
            default:
                $node = $contentContext->getNodeByIdentifier($searchHit['_source']['neos_node_identifier']);
                if (!$node instanceof TraversableNodeInterface) {
                    return null;
                }
                $packageKey = \mb_substr(
                    $searchHit['_source']['neos_type'],
                    0,
                    \mb_strpos($searchHit['_source']['neos_type'], ':') ?: null
                );
                $namespace = \str_replace('.', '\\', $packageKey);
                $cardFactoryClassName = $namespace . '\\Presentation\\Block\\Card\\CardFactory';
                /** @var CardFactoryInterface $cardFactory */
                $cardFactory = new $cardFactoryClassName();
                /** @var SlotInterface $card */
                $card = $cardFactory->forThing(
                    $node,
                    $searchHit['_source']['searchType'],
                    $highlight
                );
                $link = $this->linkFactory
                    ->forLinkToDocument($node, null)
                    ->asContainer()
                    ->withContent($card);
        }

        return new Thing(
            $searchHit['_source']['neos_node_identifier'],
            $this->fusionRenderer->render(
                '/<Hoerbiger.Shared:Block.Link>/renderer',
                [
                    'presentationObject' => $link
                ]
            )
        );
    }
}
