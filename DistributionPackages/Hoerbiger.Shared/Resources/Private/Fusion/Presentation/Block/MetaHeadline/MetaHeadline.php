<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MetaHeadline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadlineVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class MetaHeadline extends AbstractComponentPresentationObject implements MetaHeadlineInterface
{
    private MetaHeadlineVariant $variant;

    private ?SlotInterface $slice1;

    private ?SlotInterface $slice2;

    private ?SlotInterface $slice3;

    public function __construct(
        MetaHeadlineVariant $variant,
        ?SlotInterface $slice1,
        ?SlotInterface $slice2,
        ?SlotInterface $slice3
    ) {
        $this->variant = $variant;
        $this->slice1 = $slice1;
        $this->slice2 = $slice2;
        $this->slice3 = $slice3;
    }

    public function getVariant(): MetaHeadlineVariant
    {
        return $this->variant;
    }

    public function getSlice1(): ?SlotInterface
    {
        return $this->slice1;
    }

    public function getSlice2(): ?SlotInterface
    {
        return $this->slice2;
    }

    public function getSlice3(): ?SlotInterface
    {
        return $this->slice3;
    }
}
