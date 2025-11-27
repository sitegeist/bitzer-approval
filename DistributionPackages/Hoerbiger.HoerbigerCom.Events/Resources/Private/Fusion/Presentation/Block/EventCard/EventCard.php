<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Block\EventCard;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class EventCard extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ?SlotInterface $media,
        public readonly ?SlotInterface $meta,
        public readonly ?SlotInterface $header,
        public readonly SlotInterface $date,
        public readonly ?SlotInterface $eventLocation,
        public readonly ?SlotInterface $eventDetailLocation,
        public readonly ?SlotInterface $buttons
    ) {
    }
}
