<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class HeaderVideoStage extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $slides
    ) {
    }
}
