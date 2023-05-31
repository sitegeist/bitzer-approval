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
use Hoerbiger\Shared\Presentation\Layout\Stage\ApplicationWorldMediaStage\ApplicationWorldMediaStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\ApplicationWorldNavStage\ApplicationWorldNavStage;
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

    public function forApplicationWorld(TraversableNodeInterface $applicationWorld, bool $inBackend): ?ApplicationWorldMediaStage
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($applicationWorld, $inBackend);

        if (!$imageSource) {
            return null;
        }

        return new ApplicationWorldMediaStage(
            StageVariant::fullMediaTextCenter(),
            null,
            new Headline(
                HeadlineSize::SIZE_EXTRA_LARGE,
                HeadlineColor::COLOR_WHITE,
                HeadlineType::TYPE_H1,
                Editable::fromNodeProperty($applicationWorld, 'headline')
            ),
            null,
            new Text(
                TextVariant::VARIANT_CENTERED,
                Editable::fromNodeProperty($applicationWorld, 'abstract'),
                false
            ),
            null,
            $this->figureFactory->forFullMediaStage($imageSource),
        );
    }

    public function forSliderPreviewMixin(TraversableNodeInterface $targetNode, bool $inBackend): ApplicationWorldNavStage
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
        if ($mediaBackgroundImageSource instanceof AssetImageSourceHelper) {
            $mediaBackgroundImageSource->setAsync(true);
        }

        return new ApplicationWorldNavStage(
            StageVariant::fullMediaTextRight(),
            null,
            $documentHeadlineContent
                ? new Headline(
                    HeadlineSize::SIZE_LARGE,
                    HeadlineColor::COLOR_WHITE,
                    HeadlineType::div(),
                    Value::fromString($documentHeadlineContent)
                )
                : null,
            null,
            $textContent
                ? new Text(
                    TextVariant::VARIANT_TRANSPARENT_CONTRAST,
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
                HeadlineSize::SIZE_EXTRA_LARGE,
                HeadlineColor::COLOR_WHITE,
                HeadlineType::TYPE_H1,
                Value::fromString($applicationWorld->getLabel())
            ),
            new Text(
                TextVariant::VARIANT_CENTERED,
                Value::fromString($section->getLabel()),
                false
            ),
            null,
            $mediaBackgroundImageSource
                ? $this->figureFactory->forSlideStageBackground($mediaBackgroundImageSource)
                : null
        );
    }
}
