<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

interface FigureInterface extends ComponentPresentationObjectInterface
{
    public function getImageSource(): ImageSourceHelperInterface;

    public function getIsToBeLoadedLazily(): bool;

    public function getSourceSet(): SourceSet;

    public function getSizes(): Sizes;

    public function getObjectFit(): ?FigureObjectFit;

    public function getObjectPosition(): ?FigureObjectPosition;

    public function getCaption(): ?SlotInterface;
}
