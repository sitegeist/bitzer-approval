<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Video\Video;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class SliderItem extends AbstractComponentPresentationObject implements SliderItemInterface
{
    private ?Headline $headline;

    private ?Text $text;

    private Figure $figure;

    private Video $video;

    private ?Link $link;

    public function __construct(
        ?Headline $headline,
        ?Text $text,
        Figure $figure,
        Video $video,
        ?Link $link
    ) {
        $this->headline = $headline;
        $this->text = $text;
        $this->figure = $figure;
        $this->video = $video;
        $this->link = $link;
    }

    public function getHeadline(): ?Headline
    {
        return $this->headline;
    }

    public function getText(): ?Text
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

    public function getLink(): ?Link
    {
        return $this->link;
    }
}
