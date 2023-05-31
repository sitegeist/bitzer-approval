<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }

    public function forDownloadCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            false,
            $this->sourceSetProvider->getSourceSetForPresets([
                PresetIdentifier::IDENTIFIER_XS
            ]),
            new QualifiedPresetIdentifiers([]),
            '100px',
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }

    public function forProductCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getSourceSetForPresets([
                PresetIdentifier::IDENTIFIER_SM,
                PresetIdentifier::IDENTIFIER_XS
            ]),
            new QualifiedPresetIdentifiers([
                '(min-width: 768px)' => PresetIdentifier::IDENTIFIER_SM
            ]),
            '100vw',
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }

    public function forPressReleaseCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getSourceSetForPresets([
                PresetIdentifier::IDENTIFIER_SM,
                PresetIdentifier::IDENTIFIER_XS
            ]),
            new QualifiedPresetIdentifiers([
                '(min-width: 768px)' => PresetIdentifier::IDENTIFIER_SM
            ]),
            '100vw',
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }

    public function forNavigationCard(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([
                '(min-width: 1600px)' => PresetIdentifier::IDENTIFIER_MD,
                '(min-width: 768px)' => PresetIdentifier::IDENTIFIER_SM
            ]),
            '100vw',
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }

    public function forTwoColumnGrid(ImageSourceHelperInterface $imageSource): Figure
    {
        return new Figure(
            $imageSource,
            true,
            $this->sourceSetProvider->getFullSet(),
            new QualifiedPresetIdentifiers([
                '(min-width: 1600px)' => PresetIdentifier::IDENTIFIER_LG,
                '(min-width: 1024px)' => PresetIdentifier::IDENTIFIER_MD
            ]),
            '100w',
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
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
            '350px',
            FigureObjectFit::FIT_CONTAIN,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_CONTAIN,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_CONTAIN,
            FigureObjectPosition::POSITION_CENTER,
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
            FigureObjectFit::FIT_CONTAIN,
            FigureObjectPosition::POSITION_CENTER,
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
                '(min-width: 1800px)' => PresetIdentifier::IDENTIFIER_XL
            ]),
            '100w',
            FigureObjectFit::FIT_COVER,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_TOP,
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
            FigureObjectFit::FIT_COVER,
            FigureObjectPosition::POSITION_CENTER,
            null
        );
    }
}
