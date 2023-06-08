<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class CatalogRequest implements \JsonSerializable
{
    const DEFAULT_LIMIT = 10000;

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
                                        'neos_type_and_supertypes' => 'Hoerbiger.HoerbigerCom.Catalog:Tag.Product'
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

    public function withIndustrySolution(TraversableNodeInterface $industrySolution): self
    {
        $request = $this->request;
        $request['query']['bool']['filter']['bool']['must'][] = [
            'term' => [
                'industrySolutions' => $industrySolution->getNodeAggregateIdentifier()
            ]
        ];

        return new self($request);
    }

    public function withSection(TraversableNodeInterface $section): self
    {
        $request = $this->request;
        $request['query']['bool']['filter']['bool']['must'][] = [
            'term' => [
                'sections' => $section->getNodeAggregateIdentifier()
            ]
        ];

        return new self($request);
    }

    public function sortByDocumentTreePosition(): self
    {
        $request = $this->request;
        $request['sort'] = [
            [
                'neos_sort_index' => 'asc'
            ]
        ];

        return new self($request);
    }

    /**
     * @return array<string,mixed> $request
     */
    public function jsonSerialize(): array
    {
        return $this->request;
    }
}
