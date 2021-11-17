<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

/**
 * @Flow\Proxy(false)
 */
final class TabBox extends AbstractComponentPresentationObject implements TabBoxInterface
{
    private TabBoxVariant $variant;

    private Collection $tabs;

    public function __construct(
        TabBoxVariant $variant,
        Collection $tabs
    ) {
        $this->variant = $variant;
        $this->tabs = $tabs;
    }

    public function getVariant(): TabBoxVariant
    {
        return $this->variant;
    }

    public function getTabs(): Collection
    {
        return $this->tabs;
    }
}
