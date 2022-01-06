<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Flowpack\ElasticSearch\ContentRepositoryAdaptor\ElasticSearchClient;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;

/**
 * @Flow\Scope("singleton")
 */
final class Catalog
{
    /**
     * @var ElasticSearchClient
     * @Flow\Inject
     */
    protected $searchClient;

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findProductsForIndustrySolution(
        ContentContext $subgraph,
        TraversableNodeInterface $industrySolution
    ): TraversableNodes {
        $this->searchClient->setContextNode($subgraph->getCurrentSiteNode());
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();
        $request = CatalogRequest::create($siteNode);
        $request = $request->withIndustrySolution($industrySolution);

        $result = $this->searchClient->getIndex()
            ->request('GET', '/_search', [], json_encode($request) ?: null)
            ->getTreatedContent();

        $products = [];
        foreach ($result['hits']['hits'] as $searchHit) {
            /** @var ?TraversableNodeInterface $product */
            $product = $subgraph->getNodeByIdentifier($searchHit['_source']['neos_node_identifier']);
            if ($product) {
                $products[] = $product;
            }
        }

        return TraversableNodes::fromArray($products);
    }
}
