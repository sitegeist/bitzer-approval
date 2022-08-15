<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Doctrine\DBAL\Types\Type;
use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\DotNavigationItem\DotNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\IconNavigationItem\IconNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\SlidableVariant;
use Hoerbiger\Shared\Presentation\Layout\Slidable\TextNavigationItem\TextNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItem;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\Slidable;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

final class SlidableFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        StageFactory $stageFactory,
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->stageFactory = $stageFactory;
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $nodes
     */
    public function forSliderPreviewMixins(TraversableNodes $nodes, TraversableNodeInterface $productListNode, bool $inBackend): Slidable
    {
        $linkFactory = new LinkFactory();

        return new Slidable(
            SlidableVariant::applicationWorld(),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
                $link = $linkFactory->forLinkToDocument($targetNode, null);
                return $this->stageFactory->forSliderPreviewMixin($targetNode, $inBackend);
            }),
            null,
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $link = $linkFactory->forLinkToDocument($targetNode, null);

                $thumNavigationItem = new TextNavigationItem(
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                );

                if ($inBackend) {
                    return $thumNavigationItem;
                } else {
                    return $link->asNoStyles()->withContent($thumNavigationItem);
                }
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $link = $linkFactory->forLinkToDocument($targetNode, null);

                $thumNavigationItem = new DotNavigationItem();

                if ($inBackend) {
                    return $thumNavigationItem;
                } else {
                    return $link->asNoStyles()->withContent($thumNavigationItem);
                }
            }),
            ($inBackend || $productListNode->getProperty("headline"))
                ? new Headline(
                    HeadlineSize::large(),
                    HeadlineColor::white(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($productListNode, 'headline')
                )
                : null
        );
    }

    public function forApplicationWorld(TraversableNodeInterface $applicationWorld, bool $inBackend): Slidable
    {
        $sections = $applicationWorld->findNamedChildNode(NodeName::fromString('sections'))->findChildNodes();

        return new Slidable(
            SlidableVariant::regular(),
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
        $linkFactory = new LinkFactory();

        $result = Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
            $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
            if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                $thumbnailImageSource->setAsync(true);
            }
            $link = $linkFactory->forLinkToDocument($targetNode, null);

            $thumNavigationItem = new ThumbNavigationItem(
                $thumbnailImageSource
                    ? $this->figureFactory->forSlideThumbnailIcon($thumbnailImageSource)
                    : null,
                $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                ThumbNavigationItemType::mobile()
            );

            if ($inBackend) {
                return $thumNavigationItem;
            } else {
                return $link->asContainer()->withContent($thumNavigationItem);
            }
        });
        return $result;
    }
}
