<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
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
    public function __construct(
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly FigureFactory $figureFactory,
        private readonly VideoFactory $videoFactory
    ) {
    }

    /**
     * @param TraversableNodes<int,TraversableNodeInterface> $nodes
     */
    public function forSliderPreviewMixins(TraversableNodes $nodes, TraversableNodeInterface $productListNode, bool $inBackend): Slider
    {
        return new Slider(
            SliderVariant::VARIANT_APPLICATION_WORLD,
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                return $this->forSliderPreviewMixin($targetNode, $inBackend);
            }),
            Collection::fromNodes($nodes, function($targetNode) use ($inBackend) {
                $thumbnailImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
                if ($thumbnailImageSource instanceof AssetImageSourceHelper) {
                    $thumbnailImageSource->setAsync(true);
                }
                $thumNavigationItem = new SliderTextNavigationItem(
                    mb_substr($targetNode->getProperty('slider__preview__headline') ?: $targetNode->getProperty('headline') ?: '', 0, 1),
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

        $textContent = Value::fromString($targetNode->getProperty('slider__preview__description') ?: '');

        $mediaImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
        if ($mediaImageSource instanceof AssetImageSourceHelper) {
            $mediaImageSource->setAsync(true);
        }

        $mediaVideoSource = $targetNode->getProperty('video');

        return new SliderSlide(
            StageVariant::VARIANT_FULLMEDIATEXTRIGHT,
            $mediaVideoSource
                ? $this->videoFactory->forSliderVideo($targetNode, $inBackend, Theme::VALUE_SIMPLE_LOOP)
                : ($mediaImageSource
                    ? $this->figureFactory->forSlideStageIcon($mediaImageSource)
                    : null)
                ,
            $documentHeadlineContent
                ? Value::fromString($documentHeadlineContent)
                : null,
            $textContent,
            new Button(
                ButtonType::TYPE_LINK,
                ButtonVariant::VARIANT_PLAIN,
                new Grid(
                    GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable(array_filter([
                        new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1),
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
