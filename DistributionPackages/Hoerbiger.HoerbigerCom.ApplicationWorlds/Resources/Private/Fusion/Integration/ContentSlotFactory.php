<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable\SlidableFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var SlidableFactory
     */
    protected $slidableFactory;

    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.ApplicationWorlds:Content.ApplicationWorldNavigation':
                return $this->forApplicationWorldNavigationNode($contentNode, $subgraph, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    public function forApplicationWorldNavigationNode(
        TraversableNodeInterface $productListNode,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
        /** @var TraversableNodeInterface $siteNode */
        $siteNode = $subgraph->getCurrentSiteNode();
        return new ContentContainer(
            ContentContainerVariant::regular(),
            Collection::fromIterable([
                new Headline(
                    HeadlineSize::large(),
                    HeadlineColor::brand(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($productListNode, 'headline')
                ),
                $this->slidableFactory->forSliderPreviewMixins(
                    $this->findChildNodesByNodeTypeFilterString(
                        $siteNode,
                        'Hoerbiger.HoerbigerCom.ApplicationWorlds:Document.ApplicationWorld'
                    ),
                    $inBackend
                )
            ], fn (SlotInterface $slot): SlotInterface => $slot)
        );
    }
}
