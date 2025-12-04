<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Integration;

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class LinkFactory extends AbstractComponentPresentationObjectFactory
{
    public function forEvent(
        Node $event,
        bool $isExternalEvent,
        SlotInterface $eventCard,
        ?string $targetProperty
    ): Link {
        return new Link(
            LinkVariant::VARIANT_LABEL,
            $isExternalEvent
                ? new Uri($targetProperty ?: '/')
                : $this->uriService->getNodeUri($event),
            $isExternalEvent
                ? LinkTarget::TARGET_BLANK
                : LinkTarget::TARGET_SELF,
            $event->getLabel(),
            $eventCard,
            null
        );
    }
}
