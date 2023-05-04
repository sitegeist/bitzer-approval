<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Video\Video;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface SliderItemInterface extends ComponentPresentationObjectInterface
{
    public function getHeadline(): ?Headline;

    public function getText(): ?SlotInterface;

    public function getFigure(): Figure;

    public function getVideo(): Video;
}
