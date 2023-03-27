<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slider;

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
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Layout\Slidable\DotNavigationItem\DotNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slidable\SlidableVariant;
use Hoerbiger\Shared\Presentation\Layout\Slidable\TextNavigationItem\TextNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slider\Slider;
use Hoerbiger\Shared\Presentation\Layout\Slider\SliderTextNavigationItem\SliderTextNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slider\SliderVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

final class SliderFactory extends AbstractComponentPresentationObjectFactory
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
    public function forSliderPreviewMixins(TraversableNodes $nodes, TraversableNodeInterface $productListNode, bool $inBackend): Slider
    {
        $linkFactory = new LinkFactory();

        return new Slider(
            SliderVariant::applicationWorld(),
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

                $thumNavigationItem = new SliderTextNavigationItem(
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                );

                if ($inBackend) {
                    return $thumNavigationItem;
                } else {
                    return $link->asNoStyles()->withContent($thumNavigationItem);
                }
            }),
            null,
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
}
