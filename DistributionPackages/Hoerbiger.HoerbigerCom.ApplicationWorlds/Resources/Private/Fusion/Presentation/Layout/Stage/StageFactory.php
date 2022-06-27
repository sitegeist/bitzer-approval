<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage;

use Hoerbiger\Shared\Presentation\Block\Button\ButtonFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\FullMediaStage\FullMediaStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\SlideStage\SlideStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

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

    public function forApplicationWorld(TraversableNodeInterface $applicationWorld, bool $inBackend): ?FullMediaStage
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($applicationWorld, $inBackend);

        if (!$imageSource) {
            return null;
        }

        return new FullMediaStage(
            StageVariant::fullMediaTextCenter(),
            null,
            new Headline(
                HeadlineSize::large(),
                HeadlineColor::white(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($applicationWorld, 'headline')
            ),
            null,
            new Text(
                TextVariant::brandContrast(),
                Editable::fromNodeProperty($applicationWorld, 'abstract'),
                false
            ),
            null,
            $this->figureFactory->forFullMediaStage($imageSource),
        );
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
        if ($mediaImageSource instanceof AssetImageSourceHelper) {
            $mediaImageSource->setAsync(true);
        }
        $mediaBackgroundImageSource = $this->imageSourceFactory->forImageProperty($targetNode, $inBackend, 'slider__preview__background');

        return new SlideStage(
            StageVariant::fullMediaTextRight(),
            null,
            $documentHeadlineContent
                ? new Headline(
                    HeadlineSize::large(),
                    HeadlineColor::white(),
                    HeadlineType::div(),
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

    public function forSection(TraversableNodeInterface $section, TraversableNodeInterface $applicationWorld, bool $inBackend): SlideStage
    {
        $mediaBackgroundImageSource = $this->imageSourceFactory->forImageMixin($section, $inBackend);

        return new SlideStage(
            StageVariant::fullMediaTextCenter(),
            null,
            null,
            new Headline(
                HeadlineSize::large(),
                HeadlineColor::white(),
                HeadlineType::h1(),
                Value::fromString($applicationWorld->getLabel())
            ),
            null,
            null,
            $mediaBackgroundImageSource
                ? $this->figureFactory->forSlideStageBackground($mediaBackgroundImageSource)
                : null
        );
    }
}
