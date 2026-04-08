<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Block\HannsList;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class HannsList extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $cards
    ) {
    }
}
