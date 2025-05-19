<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use Sitegeist\LostInTranslation\Domain\TranslatableProperty\TranslatablePropertyNamesFactory;

class PropertySynchronizer
{
    public function __construct(
        private readonly TranslatablePropertyNamesFactory $translatablePropertyNamesFactory,
        private readonly SynchronizedPropertyFactory $synchronizedPropertyFactory,
    ) {
    }

    public function syncProperties(
        Node $sourceNode,
        Node $targetNode,
    ): void {
        $synchronizedProperties = $this->synchronizedPropertyFactory->forNodeType($sourceNode->getNodeType());
        $translatablePropertyNames = $this->translatablePropertyNamesFactory->createForNodeType(
            $sourceNode->getNodeType()
        );

        foreach ($synchronizedProperties as $synchronizedProperty) {
            // translatable properties are updated throw review process and are not synced automatically
            if ($translatablePropertyNames->isTranslatable($synchronizedProperty->name)) {
                continue;
            }

            if ($synchronizedProperty->strategy !== PropertySynchronizationStrategy::ONCE) {
                if ($synchronizedProperty->name === '_hidden') {
                    $targetNode->setHidden($sourceNode->isHidden());
                }

                if ($synchronizedProperty->name === '_hiddenBeforeDateTime') {
                    $targetNode->setHiddenBeforeDateTime($sourceNode->getHiddenBeforeDateTime());
                }

                if ($synchronizedProperty->name === '_hiddenAfterDateTime') {
                    $targetNode->setHiddenAfterDateTime($sourceNode->getHiddenAfterDateTime());
                }

                $targetNode->setHiddenInIndex($sourceNode->isHiddenInIndex());

                if ($sourceNode->getProperty($synchronizedProperty->name)
                    !== $targetNode->getProperty($synchronizedProperty->name)
                ) {
                    $targetNode->setProperty(
                        $synchronizedProperty->name,
                        $sourceNode->getProperty($synchronizedProperty->name)
                    );
                }
            }
        }
    }
}
