<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Box extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly BoxVariant $variant,
        public readonly SlotInterface $content,
        public readonly ?string $identifier = null
    ) {

    }
}
