<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slidable\IconNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class IconNavigationItem extends AbstractComponentPresentationObject implements IconNavigationItemInterface
{
    private IconInterface $icon;

    private string $label;

    public function __construct(
        IconInterface $icon,
        string $label
    ) {
        $this->icon = $icon;
        $this->label = $label;
    }

    public function getIcon(): IconInterface
    {
        return $this->icon;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
