<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;

interface FigureInterface extends ComponentPresentationObjectInterface
{
    public function getImageSource(): ImageSourceHelperInterface;

    public function getIsToBeLoadedLazily(): bool;

    public function getObjectFit(): ?FigureObjectFit;

    public function getObjectPosition(): ?FigureObjectPosition;
}
