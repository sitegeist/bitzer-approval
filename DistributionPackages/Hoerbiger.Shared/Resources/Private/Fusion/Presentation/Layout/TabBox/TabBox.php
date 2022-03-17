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

    private ?string $identifier;

    public function __construct(
        TabBoxVariant $variant,
        Collection $tabs,
        ?string $identifier = null
    ) {
        $this->variant = $variant;
        $this->tabs = $tabs;
        $this->identifier = $identifier;
    }

    public function getVariant(): TabBoxVariant
    {
        return $this->variant;
    }

    public function getTabs(): Collection
    {
        return $this->tabs;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}
