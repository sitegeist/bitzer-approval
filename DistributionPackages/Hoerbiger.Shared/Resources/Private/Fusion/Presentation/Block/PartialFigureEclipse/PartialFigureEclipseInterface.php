<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface PartialFigureEclipseInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): PartialFigureEclipseVariant;

    public function getFigure(): FigureInterface;

    public function getContent(): SlotInterface;
}
