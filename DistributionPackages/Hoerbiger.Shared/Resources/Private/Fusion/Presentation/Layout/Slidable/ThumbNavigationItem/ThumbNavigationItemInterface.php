<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;

interface ThumbNavigationItemInterface extends ComponentPresentationObjectInterface
{
    public function getFigure(): ?FigureInterface;

    public function getLabel(): string;

    public function getType(): ?ThumbNavigationItemType;
}
