<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class AnimatedContentContainer extends AbstractComponentPresentationObject
{    public function __construct(
        public readonly AnimatedContentContainerVariant $variant,
        public readonly SlotInterface $content,
        public readonly string  $visibleStateClass,
        public readonly string $invisibleStateClass
    ) {
    }
}
