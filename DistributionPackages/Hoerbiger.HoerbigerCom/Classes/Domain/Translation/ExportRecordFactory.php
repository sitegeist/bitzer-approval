<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Hoerbiger\Shared\Infrastructure\ControllerEnvironmentService;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Psr\Http\Message\UriInterface;
use Sitegeist\LostInTranslation\Domain\TranslatableProperty\TranslatablePropertyNamesFactory;

#[Flow\Scope('singleton')]
class ExportRecordFactory
{
    /**
     * @var ControllerContext
     */
    protected $controllerContext;
    public function __construct(
        private readonly TranslatablePropertyNamesFactory $translatablePropertyNamesFactory,
        private readonly ControllerEnvironmentService $controllerEnvironmentService,
    ) {
    }

    public function initializeObject(): void
    {
        $this->controllerContext = $this->controllerEnvironmentService->getControllerContext();
    }

    public function findRecordsForNode(
        ?UriInterface $documentNodeUri,
        TraversableNodeInterface $sourceNode,
        TraversableNodeInterface $targetNode
    ): ExportRecords {
        $exportRecords = [];
        $translatablePropertyNames = $this->translatablePropertyNamesFactory->createForNodeType(
            $sourceNode->getNodeType()
        );
        foreach ($translatablePropertyNames as $translatablePropertyName) {
            if (!$translatablePropertyNames->isTranslatable($translatablePropertyName->getName())) {
                continue;
            }

            $contentInSourceLanguage = $sourceNode->getProperty($translatablePropertyName->getName());

            if (!$contentInSourceLanguage) {
                continue;
            }

            $exportRecords[] = new ExportRecord(
                $documentNodeUri,
                $targetNode->getNodeAggregateIdentifier(),
                $translatablePropertyName->getName(),
                $contentInSourceLanguage,
                $targetNode->getProperty($translatablePropertyName->getName())
            );
        }
        return new ExportRecords(...$exportRecords);
    }
}
