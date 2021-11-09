<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline\MileStone;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class MileStone extends AbstractComponentPresentationObject implements MileStoneInterface
{
    private SlotInterface $year;

    private SlotInterface $label;

    private SlotInterface $description;

    private ?SlotInterface $figures;

    public function __construct(
        SlotInterface $year,
        SlotInterface $label,
        SlotInterface $description,
        ?SlotInterface $figures
    ) {
        $this->year = $year;
        $this->label = $label;
        $this->description = $description;
        $this->figures = $figures;
    }

    public function getYear(): SlotInterface
    {
        return $this->year;
    }

    public function getLabel(): SlotInterface
    {
        return $this->label;
    }

    public function getDescription(): SlotInterface
    {
        return $this->description;
    }

    public function getFigures(): ?SlotInterface
    {
        return $this->figures;
    }
}
