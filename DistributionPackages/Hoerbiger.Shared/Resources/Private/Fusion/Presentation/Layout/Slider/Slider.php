<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slider;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;


/**
 * @Flow\Proxy(false)
 */
final class Slider extends AbstractComponentPresentationObject implements SliderInterface
{
    private SliderVariant $variant;

    private SlotInterface $slides;

    private ?SlotInterface $inlineNavigation;

    private ?SlotInterface $headline;

    public function __construct(
        SliderVariant $variant,
        SlotInterface $slides,
        ?SlotInterface $inlineNavigation = null,
        ?SlotInterface $headline = null
    ) {
        $this->variant = $variant;
        $this->slides = $slides;
        $this->inlineNavigation = $inlineNavigation;
        $this->headline = $headline;
    }

    public function getVariant(): SliderVariant
    {
        return $this->variant;
    }

    public function getSlides(): SlotInterface
    {
        return $this->slides;
    }

    public function getInlineNavigation(): ?SlotInterface
    {
        return $this->inlineNavigation;
    }

    public function getHeadline(): ?SlotInterface
    {
        return $this->headline;
    }
}
