<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

#[Flow\Proxy(false)]
final class Figure extends AbstractComponentPresentationObject
{
    private Sizes $sizes;
    public function __construct(
        public readonly ImageSourceHelperInterface $imageSource,
        public readonly bool $isToBeLoadedLazily,
        public readonly SourceSet $sourceSet,
        QualifiedPresetIdentifiers $sizes,
        public readonly string $finalWidth,
        public readonly ?FigureObjectFit $objectFit,
        public readonly ?FigureObjectPosition $objectPosition,
        public readonly ?SlotInterface $caption
    ) {
        $this->sizes = new Sizes(
            array_map(function (PresetIdentifier $presetIdentifier) use($sourceSet): string {
                return $sourceSet->getWidth($presetIdentifier);
            },$sizes->getIterator()->getArrayCopy()),
            $finalWidth
        );
    }
    public function getSizes(): Sizes
    {
        return $this->sizes;
    }
}
