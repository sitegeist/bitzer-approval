<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class HeaderVideoStage extends AbstractComponentPresentationObject implements HeaderVideoStageInterface
{
    private SlotInterface $slides;

    public function __construct(
        SlotInterface $slides
    ) {
        $this->slides = $slides;
    }

    public function getSlides(): SlotInterface
    {
        return $this->slides;
    }
}
