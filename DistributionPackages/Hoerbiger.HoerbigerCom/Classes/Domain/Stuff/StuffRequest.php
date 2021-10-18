<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class StuffRequest implements \JsonSerializable
{
    const DEFAULT_LIMIT = 10;

    /**
     * @var array<string,mixed>
     */
    private array $request;

    /**
     * @param array<string,mixed> $request
     */
    private function __construct(array $request)
    {
        $this->request = $request;
    }

    public static function create(TraversableNodeInterface $contextNode): self
    {
        $path = $contextNode->findNodePath();

        return new self([
            'size' => self::DEFAULT_LIMIT,
            'query' => [
                'bool' => [
                    'filter' => [
                        'bool' => [
                            'must' => [
                                [
                                    'bool' => [
                                        'should' => [
                                            [
                                                'term' => [
                                                    'neos_parent_path' => (string)$path
                                                ]
                                            ],
                                            [
                                                'term' => [
                                                    'neos_path' => (string)$path
                                                ]
                                            ]
                                        ],
                                    ],
                                ],
                                [
                                    'term' => [
                                        'neos_type_and_supertypes' => 'Hoerbiger.Shared:Mixin.Searchable'
                                    ]
                                ]
                            ],
                            'should' => [],
                            'must_not' => [
                                [
                                    'term' => [
                                        'neos_hidden' => true,
                                    ]
                                ],
                                [
                                    'range' => [
                                        'neos_hidden_before_datetime' => [
                                            'gt' => 'now'
                                        ]
                                    ]
                                ],
                                [
                                    'range' => [
                                        'neos_hidden_after_datetime' => [
                                            'lt' => 'now'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ]);
    }

    public function withPage(int $page): self
    {
        $request = $this->request;
        $request['from'] = ($page - 1) * self::DEFAULT_LIMIT;

        return new self($request);
    }

    public function withTypes(SearchTypes $types): self
    {
        $request = $this->request;

        $request['query']['bool']['filter']['bool']['must'][] = [
            'terms' => [
                'searchType' => $types
            ],
        ];

        return new self($request);
    }

    public function withPeriod(SearchPeriod $period): self
    {
        $referenceDate = new \DateTimeImmutable();
        $referenceDate = $referenceDate->sub($period->getInterval());

        $request = $this->request;
        $request['query']['bool']['filter']['bool']['must'][] = [
            'range' => [
                'dateModified' => [
                    'gt' => $referenceDate->format('Y-m-d\TH:i:sP')
                ]
            ]
        ];

        return new self($request);
    }

    public function withSortingProperty(SortingProperty $sorting): self
    {
        $request = $this->request;

        $request['sort'] = [
            [
                $sorting->getValue() => 'desc'
            ]
        ];

        return new self($request);
    }

    public function withSearchTerm(SearchTerm $searchTerm): self
    {
        $request = $this->request;
        $request['query']['bool']['should'][] = [
            'multi_match' => [
                'query' => $searchTerm->getValue(),
                'fields' => ['neos_fulltext.*', 'title', 'headline', 'abstract', 'text'],
                'analyzer' => 'standard',
                'fuzziness' => 'AUTO'
            ]
        ];
        $request['query']['bool']['should'][] = [
            'query_string' => [
                'query' => $searchTerm->getQueryStringValue(),
                'fields' => ['neos_fulltext.*', 'title', 'headline', 'abstract', 'text'],
                'analyzer' => 'whitespace',
                'fuzziness' => 'AUTO'
            ]
        ];
        $request['query']['bool']['minimum_should_match'] = 1;
        $request['highlight'] = [
            'number_of_fragments' => 0,
            'fields' => [
                'title' => new \stdClass,
                'headline' => new \stdClass,
                'abstract' => new \stdClass,
                'text' => new \stdClass
            ]
        ];

        return new self($request);
    }

    public function withMinimumScore(float $minimumScore): self
    {
        $request = $this->request;
        $request['min_score'] = $minimumScore;

        return new self($request);
    }

    /**
     * @param array<string,mixed> $baseFilters
     */
    public function withTypeFacet(array $baseFilters): self
    {
        $request = $this->request;

        $request['aggregations']['facets']['global'] = new \stdClass();
        $request['aggregations']['facets']['aggregations']['searchType'] = [
            'filter' => [
                'bool' => $baseFilters
            ],
            'aggregations' => [
                'searchType' => [
                    'terms' => [
                        'field' => 'searchType',
                        'size' => 1000
                    ]
                ]
            ],
        ];

        return new self($request);
    }

    /**
     * @return array<string,mixed>
     */
    public function getFilters(): array
    {
        return $this->request['query']['bool'];
    }

    /**
     * @return array<string,mixed> $request
     */
    public function jsonSerialize(): array
    {
        return $this->request;
    }
}
