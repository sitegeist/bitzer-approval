<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Flowpack\ElasticSearch\ContentRepositoryAdaptor\ElasticSearchClient;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
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
    public function findAllProducts(ContentContext $subgraph): TraversableNodes
    {
        $siteNode = $subgraph->getCurrentSiteNode();

        $this->searchClient->setContextNode($siteNode);

        /** @var TraversableNodeInterface $siteNode */
        $request = CatalogRequest::create($siteNode)->sortByDocumentTreePosition();

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

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findProductsForIndustrySolution(
        ContentContext           $subgraph,
        TraversableNodeInterface $industrySolution
    ): TraversableNodes {
        $this->searchClient->setContextNode($subgraph->getCurrentSiteNode());
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();
        $request = CatalogRequest::create($siteNode);
        $request = $request->withIndustrySolution($industrySolution)
            ->sortByDocumentTreePosition();

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

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findProductsForSection(
        ContentContext           $subgraph,
        TraversableNodeInterface $section
    ): TraversableNodes {
        $this->searchClient->setContextNode($subgraph->getCurrentSiteNode());
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();
        $request = CatalogRequest::create($siteNode);
        $request = $request->withSection($section)
            ->sortByDocumentTreePosition();

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

    public function findProductGroupByNodeName(
        string         $productGroupNodeName,
        ContentContext $subGraph
    ): TraversableNodeInterface {
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subGraph->getCurrentSiteNode();

        $productGroupTags = $siteNode->findNamedChildNode(
            NodeName::fromString('catalog')
        )->findNamedChildNode(NodeName::fromString('productGroupTags'));

        return $productGroupTags->findNamedChildNode(NodeName::fromString($productGroupNodeName));
    }

    /**
     * @return array<int,string>
     */
    public function findProductGroupIdentifiers(ContentContext $subGraph): array
    {
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subGraph->getCurrentSiteNode();

        $productGroups = $siteNode->findNamedChildNode(
            NodeName::fromString('catalog')
        )->findNamedChildNode(NodeName::fromString('productGroupTags'))->findChildNodes()->toArray();

        return array_map(function (TraversableNodeInterface $productGroup): string {
            return (string)$productGroup->getNodeAggregateIdentifier();
        }, $productGroups);
    }

    /**
     * @return TraversableNodes<int,TraversableNodeInterface>
     */
    public function findUnusedProductGroups(ContentContext $subgraph): TraversableNodes
    {
        $products = $this->findAllProducts($subgraph);
        $productGroupIdentifiers = $this->findProductGroupIdentifiers($subgraph);

        $usedGroupIdentifiers = [];
        foreach ($products as $product) {
            /** @var ?TraversableNodeInterface $productGroup */
            $productGroup = $product->getProperty('productGroup');
            if (!$productGroup) {
                continue;
            }
            $usedGroupIdentifiers[] = (string)$productGroup->getNodeAggregateIdentifier();
        }

        $unusedGroupIdentifiers = array_diff($productGroupIdentifiers, $usedGroupIdentifiers);
        /** @var TraversableNodeInterface[] $unusedGroups */
        $unusedGroups = array_map(
            fn (string $groupIdentifier) => $subgraph->getNodeByIdentifier($groupIdentifier),
            $unusedGroupIdentifiers
        );

        return TraversableNodes::fromArray($unusedGroups);
    }
}
