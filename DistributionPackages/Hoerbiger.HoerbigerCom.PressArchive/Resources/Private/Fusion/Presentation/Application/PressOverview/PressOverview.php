<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class PressOverview extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly PressOverviewVariant $variant,
        /** @var array<string,string> */
        public readonly array $labels,
        public readonly SlotInterface $cards
    ) {
    }
}
