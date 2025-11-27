<?php

/*
 * This file is part of the EulerHermes.Calendar package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Domain;

use Hoerbiger\HoerbigerCom\Domain\Stuff\SearchPage;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class EventsSearchRequest implements \JsonSerializable
{
    public const DEFAULT_LIMIT = 12;

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

    public static function create(): self
    {
        return new self([
            'size' => self::DEFAULT_LIMIT,
            'query' => [
                'bool' => [
                    'filter' => [
                        'bool' => [
                            'must' => [
                                [
                                    'terms' => [
                                        'neos_type_and_supertypes' => [
                                            'Hoerbiger.HoerbigerCom.Events:Document.Event',
                                            'Hoerbiger.HoerbigerCom.Events:Document.ExternalEvent',
                                        ]
                                    ]
                                ],
                                [
                                    'term' => [
                                        'neos_workspace' => 'live'
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

    public function withPage(SearchPage $page): self
    {
        $request = $this->request;
        $request['from'] = ($page->value - 1) * self::DEFAULT_LIMIT;

        return new self($request);
    }

    public function withArchiveState(bool $archived): self
    {
        $request = $this->request;

        $todayMidnight = (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))
            ->setTime(0, 0, 0);


        if ($archived) {
            $request['query']['bool']['filter']['bool']['must'][] = [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'range' => [
                                            'startDate' => [
                                                'lt' => $todayMidnight->format('Y-m-d\TH:i:sP')
                                            ]
                                        ],
                                    ],
                                    [
                                        'bool' => [
                                            'must_not' => [
                                                [
                                                    'exists' => [
                                                        'field' => 'endDate'
                                                    ]
                                                ]
                                            ]
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'range' => [
                                'endDate' => [
                                    'lt' => $todayMidnight->format('Y-m-d\TH:i:sP')
                                ]
                            ],
                        ],

                    ],
                ],
            ];
        } else {
            $request['query']['bool']['filter']['bool']['must'][] = [
                'bool' => [
                    'should' => [
                        [
                            'range' => [
                                'startDate' => [
                                    'gte' => $todayMidnight->format('Y-m-d\TH:i:sP')
                                ]
                            ],
                        ],
                        [
                            'range' => [
                                'endDate' => [
                                    'gte' => $todayMidnight->format('Y-m-d\TH:i:sP')
                                ]
                            ],
                        ],
                    ],
                ],
            ];
        }

        return new self($request);
    }

    public function withLimit(int $limit): self
    {
        $request = $this->request;
        $request['size'] = $limit;

        return new self($request);
    }

    public function withDateSorting(bool $archived): self
    {
        $request = $this->request;
        $request['sort'] = [
            [
                'startDate' => $archived ? 'desc' : 'asc'
            ]
        ];

        return new self($request);
    }

    public function withPropertyValue(string $propertyName, string $value): self
    {
        $request = $this->request;

        $request['query']['bool']['filter']['bool']['must'][] = [
            'term' => [
                $propertyName => $value
            ],
        ];

        return new self($request);
    }

    /**
     * @param array<int,mixed> $values
     */
    public function withPropertyValues(string $propertyName, array $values): self
    {
        $request = $this->request;

        $request['query']['bool']['filter']['bool']['must'][] = [
            'terms' => [
                $propertyName => $values
            ],
        ];

        return new self($request);
    }

    /**
     * @param array<int,string> $propertyNames
     */
    public function facet(array $propertyNames): self
    {
        $request = $this->request;
        $baseFilters = $this->getFilters();

        $request['aggregations']['facets']['global'] = new \stdClass();
        foreach ($propertyNames as $propertyName) {
            $filters = $baseFilters;
            $filters['filter']['bool']['must'] = [];
            foreach ($baseFilters['filter']['bool']['must'] as $filter) {
                if (!isset($filter['term'][$propertyName])) {
                    $filters['filter']['bool']['must'][] = $filter;
                }
            }

            $request['aggregations']['facets']['aggregations'][$propertyName] = [
                'filter' => [
                    'bool' => $filters
                ],
                'aggregations' => [
                    $propertyName => [
                        'terms' => [
                            'field' => $propertyName,
                            'size' => 1000,
                            'min_doc_count' => 1
                        ]
                    ]
                ],
            ];
        }

        return new self($request);
    }

    /**
     * @return array<string,mixed>
     */
    private function getFilters(): array
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
