<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Aspect;

use Hoerbiger\HoerbigerCom\Domain\Translation\PropertySynchronizer;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Sitegeist\LostInTranslation\Eel\TranslationHelper;
use Sitegeist\LostInTranslation\Ui\Changes\UpdateOutdatedTranslations;

/**
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
final class LastUpdateAction
{
    #[Flow\Inject]
    protected TranslationHelper $translationHelper;

    #[Flow\Inject]
    protected PropertySynchronizer $propertySynchronizer;

    #[Flow\Around("method(Sitegeist\LostInTranslation\Ui\Changes\UpdateOutdatedTranslations->apply())")]
    public function updateLastUpdateActionProperty(JoinPointInterface $joinPoint): void
    {
        /** @var UpdateOutdatedTranslations $objectProxy */
        $objectProxy = $joinPoint->getProxy();

        $result = $this->translationHelper->compareDocumentWithDimension($objectProxy->getSubject(), 'en');

        $outdatedNodeReferences = $result->getOutdated();


        $joinPoint->getAdviceChain()->proceed($joinPoint);

        foreach ($outdatedNodeReferences as $outdatedNodeReference) {
            /** @var Node $node */
            $node = $outdatedNodeReference->getNode();
            /** @var Node $referenceNode */
            $referenceNode = $outdatedNodeReference->getReferenceNode();

            $this->propertySynchronizer->syncProperties(
                $referenceNode,
                $node
            );
            // in order to force changing the last modification datetime in case sync strategy is once
            // and removing the update button from notification dialogue box
            $node->setProperty(
                'lastUpdateAction',
                (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s')
            );
        }
    }
}
