<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Slidable\TextNavigationItem;

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class TextNavigationItem extends AbstractComponentPresentationObject implements TextNavigationItemInterface
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
