<?php

/*
 * This file is part of the EulerHermes.Calendar package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Domain;

use Hoerbiger\HoerbigerCom\Domain\Stuff\AvailableFilters;
use Hoerbiger\HoerbigerCom\Domain\Stuff\StuffContentContextFactory;
use Hoerbiger\HoerbigerCom\Events\Integration\ThingFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Flowpack\ElasticSearch\ContentRepositoryAdaptor\ElasticSearchClient;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;

#[Flow\Scope('singleton')]
final class Calendar
{
    public function __construct(
        private readonly ElasticSearchClient $searchClient,
        private readonly StuffContentContextFactory $contentContextFactory,
        private readonly ThingFactory $thingFactory,
    ) {
    }

    public function findByQuery(CalendarQuery $query): CalendarResponse
    {
        $subgraph = $this->contentContextFactory->createContentContext($query->locale);
        /** @var Node $contextNode */
        $contextNode = $subgraph->getNodeByIdentifier($query->calendarId) ?: $subgraph->getCurrentSiteNode();
        $this->searchClient->setContextNode($contextNode);
        $request = EventsSearchRequest::create($contextNode);

        foreach ($query->activeFilters->items as $activeFilter) {
            if ($activeFilter->value) {
                $request = $request->withPropertyValue($activeFilter->name, $activeFilter->value);
            }
        }
        $request = $request->withArchiveState($query->archived);

        $request = $request->facet([
            'countryCode',
            '__year',
            'type',
        ]);
        $request = $request->withDateSorting($query->archived)
            ->withPage($query->page);

        $result = $this->searchClient->getIndex()
            ->request('GET', '/_search', [], json_encode($request) ?: null)
            ->getTreatedContent();

        $totalNumberOfResults = $result['hits']['total']['value'];

        $thingNodes = TraversableNodes::fromArray(
            array_filter(
                array_map(
                    fn (array $searchHit): ?TraversableNodeInterface => $this->resolveNode($subgraph, $searchHit),
                    $result['hits']['hits']
                )
            )
        );

        return new CalendarResponse(
            numberOfPages: (int)ceil($totalNumberOfResults / EventsSearchRequest::DEFAULT_LIMIT),
            results: $this->thingFactory->forNodes($thingNodes),
            totalNumberOfResults: $totalNumberOfResults,
            availableFilters: AvailableFilters::fromFacetAggregation($result['aggregations']['facets'])
        );
    }

    /**
     * @return TraversableNodes<int,Node>
     */
    public function findLatest(
        ?NodeAggregateIdentifier $industry,
        ?string $country,
        int $numberOfEvents,
        ContentContext $subgraph
    ): TraversableNodes {
        /** @var Node $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();
        $this->searchClient->setContextNode($siteNode);
        $request = EventsSearchRequest::create($siteNode);

        $request = $request->withLimit($numberOfEvents)
            ->withArchiveState(false)
            ->withDateSorting(false);

        $result = $this->searchClient->getIndex()
            ->request('GET', '/_search', [], json_encode($request) ?: null)
            ->getTreatedContent();

        return TraversableNodes::fromArray(
            array_filter(
                array_map(
                    fn (array $searchHit): ?TraversableNodeInterface => $this->resolveNode($subgraph, $searchHit),
                    $result['hits']['hits']
                )
            )
        );
    }

    /**
     * @param array<string,mixed> $searchHit
     */
    private function resolveNode(ContentContext $subgraph, array $searchHit): ?TraversableNodeInterface
    {
        $node = $subgraph->getNodeByIdentifier($searchHit['_source']['neos_node_identifier']);

        return $node instanceof TraversableNodeInterface
            ? $node
            : null;
    }
}
