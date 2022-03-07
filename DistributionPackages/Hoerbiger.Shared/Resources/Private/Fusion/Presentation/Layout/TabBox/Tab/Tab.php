<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\TabBox\Tab;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;

/**
 * @Flow\Proxy(false)
 */
final class Tab extends AbstractComponentPresentationObject implements TabInterface
{
    private SlotInterface $label;

    private ?IconInterface $tabIcon;

    private ?IconInterface $accordionIcon;

    private SlotInterface $content;

    public function __construct(
        SlotInterface $label,
        ?IconInterface $tabIcon,
        ?IconInterface $accordionIcon,
        SlotInterface $content
    ) {
        $this->label = $label;
        $this->tabIcon = $tabIcon;
        $this->accordionIcon = $accordionIcon;
        $this->content = $content;
    }

    public function getLabel(): SlotInterface
    {
        return $this->label;
    }

    public function getTabIcon(): ?IconInterface
    {
        return $this->tabIcon;
    }

    public function getAccordionIcon(): ?IconInterface
    {
        return $this->accordionIcon;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
