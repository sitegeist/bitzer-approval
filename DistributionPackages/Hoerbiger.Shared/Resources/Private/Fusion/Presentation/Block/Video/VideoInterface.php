<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;

interface VideoInterface extends ComponentPresentationObjectInterface
{
    public function getSource(): string;

    public function getCoverImage(): ?FigureInterface;
}
