<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\SlideStage\SlideStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    public function forApplicationWorld(TraversableNodeInterface $documents, bool $inBackend): SlotInterface
    {
        return Value::fromString('to be implemented');
    }

    public function forSliderPreviewMixin(TraversableNodeInterface $targetNode, bool $inBackend): SlideStage
    {
        $documentHeadlineContent = $targetNode->getProperty('slider__preview__headline')
            ?: $targetNode->getProperty('headline')
            ?: null;

        $textContent = $targetNode->getProperty('slider__preview__description')
            ? Value::fromString($targetNode->getProperty('slider__preview__description') ?: '')
            : null;

        $mediaImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__image');
        $mediaBackgroundImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__background');

        return new SlideStage(
            StageVariant::fullMediaTextRight(),
            null,
            $documentHeadlineContent
                ? new Headline(
                    HeadlineVariant::noBrand(),
                    HeadlineType::h2(),
                    Value::fromString($documentHeadlineContent)
                )
                : null,
            null,
            $textContent
                ? new Text(
                    TextVariant::transparentContrast(),
                    $textContent,
                    false
                )
                : null,
            $mediaImageSource
                ? $this->figureFactory->forSlideStageIcon($mediaImageSource)
                : null,
            $mediaBackgroundImageSource
                ? $this->figureFactory->forSlideStageBackground($mediaBackgroundImageSource)
                : null
        );
    }
}
