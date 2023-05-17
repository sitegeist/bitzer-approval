<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderSlide;

use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class SliderSlide extends AbstractComponentPresentationObject implements SliderSlideInterface
{
    private StageVariant $variant;

    private ?SlotInterface $media;

    private ?SlotInterface $headline;

    private ?SlotInterface $text;

    private ?SlotInterface $button;

    public function __construct(
        StageVariant $variant,
        ?SlotInterface $media,
        ?SlotInterface $headline,
        ?SlotInterface $text,
        ?SlotInterface $button = null
    ) {
        $this->variant = $variant;
        $this->media = $media;
        $this->headline = $headline;
        $this->text = $text;
        $this->button = $button;
    }

    public function getVariant(): StageVariant
    {
        return $this->variant;
    }

    public function getMedia(): ?SlotInterface
    {
        return $this->media;
    }

    public function getHeadline(): ?SlotInterface
    {
        return $this->headline;
    }

    public function getText(): ?SlotInterface
    {
        return $this->text;
    }

    public function getButton(): ?SlotInterface
    {
        return $this->button;
    }
}
