<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Row;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

#[Flow\Proxy(false)]
final class Row extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly RowVariant $variant,
        public readonly Collection $content
    ) {
    }
}
