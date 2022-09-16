<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
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
            FigureObjectPosition::center(),
            null
        );
    }

    public function forDownloadCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            false,
            $this->sourceSetProvider->getSourceSetForPresets([
                PresetIdentifier::xs()
            ]),
            new QualifiedPresetIdentifiers([]),
            '100px',
            FigureObjectFit::cover(),
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
        );
    }

    public function forTextBlockImage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            null,
            null,
            null
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
            FigureObjectPosition::center(),
            null
        );
    }

    public function forFullWidthImage(ImageSourceHelperInterface $imageSource, ?SlotInterface $caption): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            null,
            null,
            $caption
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
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
            FigureObjectPosition::center(),
            null
        );
    }

    public function forStackStage(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            false,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([
                QualifiedPresetIdentifiers::getMinWidthQualifierForBreakPoint(
                    ContentContainer::MAX_CONTENT_WIDTH
                ) => PresetIdentifier::xl()
            ]),
            '100w',
            null,
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
            FigureObjectPosition::top(),
            null
        );
    }

    public function forPeopleStory(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([]),
            '100w',
            FigureObjectFit::cover(),
            FigureObjectPosition::center(),
            null
        );
    }
}
