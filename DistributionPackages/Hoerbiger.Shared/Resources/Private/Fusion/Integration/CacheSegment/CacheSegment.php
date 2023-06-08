<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Integration\CacheSegment;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class CacheSegment extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $content,
        public readonly string $prototypeName
    ) {
    }
}
