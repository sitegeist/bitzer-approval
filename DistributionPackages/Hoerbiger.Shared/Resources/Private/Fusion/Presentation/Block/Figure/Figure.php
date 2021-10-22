<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

/**
 * @Flow\Proxy(false)
 */
final class Figure extends AbstractComponentPresentationObject implements FigureInterface
{
    private ImageSourceHelperInterface $imageSource;

    private bool $isToBeLoadedLazily;

    private SourceSet $sourceSet;

    private Sizes $sizes;

    private ?FigureObjectFit $objectFit;

    private ?FigureObjectPosition $objectPosition;

    public function __construct(
        ImageSourceHelperInterface $imageSource,
        bool $isToBeLoadedLazily,
        SourceSet $sourceSet,
        QualifiedPresetIdentifiers $sizes,
        string $finalWidth,
        ?FigureObjectFit $objectFit,
        ?FigureObjectPosition $objectPosition
    ) {
        $this->imageSource = $imageSource;
        $this->isToBeLoadedLazily = $isToBeLoadedLazily;
        $this->sourceSet = $sourceSet;
        $this->sizes = new Sizes(
            array_map(function (PresetIdentifier $presetIdentifier) use($sourceSet): string {
                return $sourceSet->getWidth($presetIdentifier);
            },$sizes->getIterator()->getArrayCopy()),
            $finalWidth
        );
        $this->objectFit = $objectFit;
        $this->objectPosition = $objectPosition;
    }

    public function getImageSource(): ImageSourceHelperInterface
    {
        return $this->imageSource;
    }

    public function getIsToBeLoadedLazily(): bool
    {
        return $this->isToBeLoadedLazily;
    }

    public function getSourceSet(): SourceSet
    {
        return $this->sourceSet;
    }

    public function getSizes(): Sizes
    {
        return $this->sizes;
    }

    public function getObjectFit(): ?FigureObjectFit
    {
        return $this->objectFit;
    }

    public function getObjectPosition(): ?FigureObjectPosition
    {
        return $this->objectPosition;
    }

    public function withObjectFitAndPosition(FigureObjectFit $objectFit, FigureObjectPosition $objectPosition): self
    {
        $clone = clone $this;
        $clone->objectFit = $objectFit;
        $clone->objectPosition = $objectPosition;

        return $clone;
    }
}
