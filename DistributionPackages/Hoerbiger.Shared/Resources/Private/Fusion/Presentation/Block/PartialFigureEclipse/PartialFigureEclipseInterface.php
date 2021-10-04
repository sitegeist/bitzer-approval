<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;

interface PartialFigureEclipseInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): PartialFigureEclipseVariant;

    public function getFigure(): FigureInterface;

    public function getHeadline(): ?HeadlineInterface;

    public function getText(): TextInterface;

    public function getLink(): ?LinkInterface;
}
