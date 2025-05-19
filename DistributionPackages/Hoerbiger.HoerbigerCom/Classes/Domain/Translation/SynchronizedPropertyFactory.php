<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\Flow\Annotations as Flow;

final class SynchronizedPropertyFactory
{
    /**
     * @var array<string, SynchronizedProperties>
     */
    protected $firstLevelCache = [];
    public function __construct()
    {
    }

    public function forNodeType(NodeType $nodeType): SynchronizedProperties
    {
        if (array_key_exists($nodeType->getName(), $this->firstLevelCache)) {
            return $this->firstLevelCache[$nodeType->getName()];
        }
        $propertyDefinitions = $nodeType->getProperties();
        $synchronizedProperties = [];
        foreach ($propertyDefinitions as $propertyName => $propertyDefinition) {
            $synchronizedProperties[] = new SynchronizedProperty(
                $propertyName,
                PropertySynchronizationStrategy::tryFrom(
                    $propertyDefinitions[$propertyName]['options']['synchronizationStrategy'] ?? ''
                )
            );
        }
        $this->firstLevelCache[$nodeType->getName()] = new SynchronizedProperties(...$synchronizedProperties);
        return $this->firstLevelCache[$nodeType->getName()];
    }
}
