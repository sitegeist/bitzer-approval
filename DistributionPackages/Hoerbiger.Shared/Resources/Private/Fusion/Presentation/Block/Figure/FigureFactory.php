<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

final class FigureFactory extends AbstractComponentPresentationObjectFactory
{
    private SourceSetProvider $sourceSetProvider;

    public function __construct(SourceSetProvider $sourceSetProvider)
    {
        $this->sourceSetProvider = $sourceSetProvider;
    }

    public function forArticleCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100vw',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forDownloadCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            false,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100vw',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forProductCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getSourceSetForPresets([
                PresetIdentifier::sm(),
                PresetIdentifier::xs()
            ]),
            new QualifiedPresetIdentifiers([
                '(min-width: 768px)' => PresetIdentifier::sm()
            ]),
            '100vw',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forNavigationCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100vw',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forTwoColumnGrid(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forGalleryImage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::contain(),
            FigureObjectPosition::center()
        );
    }

    public function forFullWidthImage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            null,
            null
        );
    }

    public function forPartialEclipse(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forDuoStage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forFullMediaStage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forSlideStageIcon(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::contain(),
            FigureObjectPosition::center()
        );
    }

    public function forSlideStageBackground(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }

    public function forSlideThumbnailIcon(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::contain(),
            FigureObjectPosition::center()
        );
    }

    public function forMileStoneImage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::contain(),
            FigureObjectPosition::center()
        );
    }

    public function forStackStage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            false,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            null,
            null
        );
    }

    public function forVideoPoster(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center()
        );
    }
}
