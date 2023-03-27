<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slider\SliderTextNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class SliderTextNavigationItem extends AbstractComponentPresentationObject implements SliderTextNavigationItemInterface
{
    private string $label;

    public function __construct(
        string $label
    ) {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
