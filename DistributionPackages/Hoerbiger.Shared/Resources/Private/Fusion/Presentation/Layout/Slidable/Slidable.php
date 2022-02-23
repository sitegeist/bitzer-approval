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

    public function __construct(
        SlotInterface $slides,
        ?SlotInterface $thumbs,
        ?bool $isApplicationWorld = false
    ) {
        $this->slides = $slides;
        $this->thumbs = $thumbs;
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

    public function getIsApplicationWorld(): ?bool
    {
        return $this->isApplicationWorld;
    }
}
