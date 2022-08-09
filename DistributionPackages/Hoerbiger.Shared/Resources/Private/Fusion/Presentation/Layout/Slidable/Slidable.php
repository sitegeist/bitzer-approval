<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;


/**
 * @Flow\Proxy(false)
 */
final class Slidable extends AbstractComponentPresentationObject implements SlidableInterface
{
    private SlidableVariant $variant;

    private SlotInterface $slides;

    private ?SlotInterface $thumbs;

    private ?SlotInterface $inlineNavigation;

    private ?SlotInterface $dots;

    private ?SlotInterface $headline;

    private ?bool $isApplicationWorld;

    public function __construct(
        SlidableVariant $variant,
        SlotInterface $slides,
        ?SlotInterface $thumbs,
        ?SlotInterface $inlineNavigation = null,
        ?SlotInterface $dots = null,
        ?SlotInterface $headline = null,
        ?bool $isApplicationWorld = false
    ) {
        $this->variant = $variant;
        $this->slides = $slides;
        $this->thumbs = $thumbs;
        $this->inlineNavigation = $inlineNavigation;
        $this->dots = $dots;
        $this->headline = $headline;
        $this->isApplicationWorld = $isApplicationWorld;
    }

    public function getVariant(): SlidableVariant
    {
        return $this->variant;
    }

    public function getSlides(): SlotInterface
    {
        return $this->slides;
    }

    public function getThumbs(): ?SlotInterface
    {
        return $this->thumbs;
    }

    public function getInlineNavigation(): ?SlotInterface
    {
        return $this->inlineNavigation;
    }

    public function getDots(): ?SlotInterface
    {
        return $this->dots;
    }

    public function getHeadline(): ?SlotInterface
    {
        return $this->headline;
    }

    public function getIsApplicationWorld(): ?bool
    {
        return $this->isApplicationWorld;
    }
}
