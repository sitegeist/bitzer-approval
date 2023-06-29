<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class TabBox extends AbstractComponentPresentationObject implements TabBoxInterface
{
    private TabBoxVariant $variant;

    private ?Headline $headline;

    private Collection $tabs;

    private ?string $identifier;

    private ?Theme $theme;

    public function __construct(
        TabBoxVariant $variant,
        ?Headline $headline,
        Collection $tabs,
        ?string $identifier = null,
        ?Theme $theme = null
    ) {
        $this->variant = $variant;
        $this->headline = $headline;
        $this->tabs = $tabs;
        $this->identifier = $identifier;
        $this->theme = $theme;
    }

    public function getVariant(): TabBoxVariant
    {
        return $this->variant;
    }

    public function getHeadline(): ?Headline
    {
        return $this->headline;
}

    public function getTabs(): Collection
    {
        return $this->tabs;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }
}
