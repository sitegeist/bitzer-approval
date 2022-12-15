<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog.Calculator package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Calculator\Presentation\Application\MiniCalculator\MiniCalculatorFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var MiniCalculatorFactory
     */
    protected $miniCalculatorFactory;

    public function forContentNode(
        TraversableNodeInterface $contentNode,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.Catalog.Calculator:Content.MiniCalculator':
                return $this->forMiniCalculatorNode();
            default:
                throw new \InvalidArgumentException(
                    'Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName(),
                    1671027834
                );
        }
    }

    public function forMiniCalculatorNode(): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            $this->miniCalculatorFactory->create()
        );
    }
}
