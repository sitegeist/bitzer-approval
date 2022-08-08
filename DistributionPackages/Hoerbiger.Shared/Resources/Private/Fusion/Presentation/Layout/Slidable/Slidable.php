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
    private SlotInterface $slides;

    private ?SlotInterface $thumbs;

    private ?SlotInterface $inlineNavigation;

    private ?SlotInterface $dots;

    private ?bool $isApplicationWorld;

    public function __construct(
        SlotInterface $slides,
        ?SlotInterface $thumbs,
        ?SlotInterface $inlineNavigation = null,
        ?SlotInterface $dots = null,
        ?bool $isApplicationWorld = false
    ) {
        $this->slides = $slides;
        $this->thumbs = $thumbs;
        $this->inlineNavigation = $inlineNavigation;
        $this->dots = $dots;
        $this->isApplicationWorld = $isApplicationWorld;
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

    public function getIsApplicationWorld(): ?bool
    {
        return $this->isApplicationWorld;
    }
}
