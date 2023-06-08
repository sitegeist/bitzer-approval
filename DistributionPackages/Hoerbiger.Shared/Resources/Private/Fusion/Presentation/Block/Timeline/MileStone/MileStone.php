<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline\MileStone;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class MileStone extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $year,
        public readonly SlotInterface $label,
        public readonly SlotInterface $description,
        public readonly ?SlotInterface $figures
    ) {
    }
}
