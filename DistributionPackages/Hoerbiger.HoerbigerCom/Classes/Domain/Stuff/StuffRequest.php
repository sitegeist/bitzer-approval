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

    /**
     * @return array<string,mixed> $request
     */
    public function jsonSerialize(): array
    {
        return $this->request;
    }
}
