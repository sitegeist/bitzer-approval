<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Video\Video;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class SliderItem extends AbstractComponentPresentationObject implements SliderItemInterface
{
    private ?Headline $headline;

    private ?SlotInterface $text;

    private Figure $figure;

    private Video $video;

    public function __construct(
        ?Headline $headline,
        ?SlotInterface $text,
        Figure $figure,
        Video $video
    ) {
        $this->headline = $headline;
        $this->text = $text;
        $this->figure = $figure;
        $this->video = $video;
    }

    public function getHeadline(): ?Headline
    {
        return $this->headline;
    }

    public function getText(): ?SlotInterface
    {
        return $this->text;
    }

    public function getFigure(): Figure
    {
        return $this->figure;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}
