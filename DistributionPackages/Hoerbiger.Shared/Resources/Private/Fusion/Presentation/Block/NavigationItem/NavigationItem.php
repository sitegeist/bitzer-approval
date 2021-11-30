<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;

/**
 * @Flow\Proxy(false)
 */
final class NavigationItem extends AbstractComponentPresentationObject implements NavigationItemInterface
{
    private LinkInterface $link;

    private ?NavigationItems $subItems;

    private bool $isActive;

    public function __construct(
        LinkInterface $link,
        ?NavigationItems $subItems,
        bool $isActive
    ) {
        $this->link = $link;
        $this->subItems = $subItems;
        $this->isActive = $isActive;
    }

    public function getLink(): LinkInterface
    {
        return $this->link;
    }

    public function getSubItems(): ?NavigationItems
    {
        return $this->subItems;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}
