<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\DotNavigationItem\DotNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\SlidableVariant;
use Hoerbiger\Shared\Presentation\Layout\Slidable\TextNavigationItem\TextNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

final class SlidableFactory extends AbstractComponentPresentationObjectFactory
{

    public function __construct(
        private readonly StageFactory $stageFactory,
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly FigureFactory $figureFactory,
        private readonly LinkFactory $linkFactory,
    ) {
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $nodes
     */
    public function forSliderPreviewMixins(TraversableNodes $nodes, TraversableNodeInterface $productListNode, bool $inBackend): Slidable
    {
        return new Slidable(
            SlidableVariant::VARIANT_APPLICATION_WORLD,
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                return $this->stageFactory->forSliderPreviewMixin($targetNode, $inBackend);
            }),
            null,
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $link = $this->linkFactory->forLinkToDocument($targetNode, null);

                $thumNavigationItem = new TextNavigationItem(
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                );

                if ($inBackend) {
                    return $thumNavigationItem;
                } else {
                    return $link->asNoStyles()->withContent($thumNavigationItem);
                }
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $link = $this->linkFactory->forLinkToDocument($targetNode, null);

                $thumNavigationItem = new DotNavigationItem();

                if ($inBackend) {
                    return $thumNavigationItem;
                } else {
                    return $link->asNoStyles()->withContent($thumNavigationItem);
                }
            }),
            ($inBackend || $productListNode->getProperty("headline"))
                ? new Headline(
                    HeadlineSize::SIZE_LARGE,
                    HeadlineColor::COLOR_WHITE,
                    HeadlineType::TYPE_H2,
                    Editable::fromNodeProperty($productListNode, 'headline')
                )
                : null
        );
    }

    public function forApplicationWorld(TraversableNodeInterface $applicationWorld, bool $inBackend): Slidable
    {
        $sections = $applicationWorld->findNamedChildNode(NodeName::fromString('sections'))->findChildNodes();

        return new Slidable(
            SlidableVariant::VARIANT_REGULAR,
            Collection::fromNodes($sections, function($targetNode) use ($inBackend, $applicationWorld): SlotInterface {
                return $this->stageFactory->forSection($targetNode, $applicationWorld, $inBackend);
            }),
            null,
            null,
            null,
            null,
            true
        );
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $nodes
     */
    public function forThumbs(TraversableNodes $nodes, bool $inBackend): SlotInterface
    {
        return Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
            $thumbnailImageSource = $this->imageSourceFactory->forImageProperty(
                $targetNode,
                $inBackend,
                'slider__preview__image'
            );
            if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                $thumbnailImageSource->setAsync(true);
            }
            $link = $this->linkFactory->forLinkToDocument($targetNode, null);

            $thumNavigationItem = new ThumbNavigationItem(
                $thumbnailImageSource
                    ? $this->figureFactory->forSlideThumbnailIcon($thumbnailImageSource)
                    : null,
                $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                ThumbNavigationItemType::TYPE_MOBILE
            );

            if ($inBackend) {
                return $thumNavigationItem;
            } else {
                return $link->asContainer()->withContent($thumNavigationItem);
            }
        });
    }
}
