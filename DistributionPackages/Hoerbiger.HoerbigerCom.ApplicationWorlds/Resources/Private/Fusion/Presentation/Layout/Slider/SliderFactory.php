<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage\StageFactory;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Video\VideoFactory;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Slider\Slider;
use Hoerbiger\Shared\Presentation\Layout\Slider\SliderSlide\SliderSlide;
use Hoerbiger\Shared\Presentation\Layout\Slider\SliderTextNavigationItem\SliderTextNavigationItem;
use Hoerbiger\Shared\Presentation\Layout\Slider\SliderVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

final class SliderFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    private VideoFactory $videoFactory;

    public function __construct(
        StageFactory $stageFactory,
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        VideoFactory $videoFactory
    ) {
        $this->stageFactory = $stageFactory;
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->videoFactory = $videoFactory;
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
                return $this->forSliderPreviewMixin($targetNode, $inBackend);
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend, $linkFactory) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $link = $linkFactory->forLinkToDocument($targetNode, null);

                $thumNavigationItem = new SliderTextNavigationItem(
                    $targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '',
                );

                return $thumNavigationItem;
            }),
            ($inBackend || $productListNode->getProperty("headline"))
                ? Editable::fromNodeProperty($productListNode, 'headline')
                : null,
        );
    }

    public function forSliderPreviewMixin(TraversableNodeInterface $targetNode, bool $inBackend): SliderSlide
    {
        $documentHeadlineContent = $targetNode->getProperty('slider__preview__headline')
            ?: $targetNode->getProperty('headline')
                ?: null;

        $textContent = $targetNode->getProperty('slider__preview__description')
            ? Value::fromString($targetNode->getProperty('slider__preview__description') ?: '')
            : Value::fromString('');

        $mediaImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
        if ($mediaImageSource instanceof AssetImageSourceHelper) {
            $mediaImageSource->setAsync(true);
        }

        $mediaVideoSource = $targetNode->getProperty('video');

        return new SliderSlide(
            StageVariant::fullMediaTextRight(),
            $mediaVideoSource
                ? $this->videoFactory->forSliderVideo($targetNode, $inBackend, Theme::simpleLoop())
                : ($mediaImageSource
                    ? $this->figureFactory->forSlideStageIcon($mediaImageSource)
                    : null)
                ,
            $documentHeadlineContent
                ? Value::fromString($documentHeadlineContent)
                : null,
            $textContent,
            new Button(
                ButtonType::link(),
                ButtonVariant::plain(),
                new Grid(
                    GridVariant::iconLeft(),
                    Collection::fromIterable(array_filter([
                        new Icon('shared', 'arrowRight', IconSize::size_1()),
                        Value::fromString(
                            $this->translator->translateById('link.label', [], null, null, 'Block.Card.NavigationCard', 'Hoerbiger.HoerbigerCom') ?: ''
                        )
                    ]))
                ),
                $this->uriService->getNodeUri($targetNode),
                '_self',
                null,
            )
        );
    }
}
