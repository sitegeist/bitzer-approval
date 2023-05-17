<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\AnchorNavigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\AnchorNavigation\AnchorNavigationVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class AnchorNavigation extends AbstractComponentPresentationObject implements AnchorNavigationInterface
{
    private AnchorNavigationVariant $variant;

    /**
     * @var array<int,Link>
     */
    private array $anchors;

    /**
     * @param array<int,Link> $anchors
     */
    public function __construct(
        AnchorNavigationVariant $variant,
        array $anchors
    ) {
        $this->variant = $variant;
        $this->anchors = $anchors;
    }

    public function getVariant(): AnchorNavigationVariant
    {
        return $this->variant;
    }

    /**
     * @return array<int,Link>
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }
}
