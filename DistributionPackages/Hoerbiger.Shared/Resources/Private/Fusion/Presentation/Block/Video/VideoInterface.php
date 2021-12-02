<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface VideoInterface extends ComponentPresentationObjectInterface
{
    public function getSource(): string;

    public function getFigure(): ?FigureInterface;

    public function getCaption(): ?SlotInterface;
}
