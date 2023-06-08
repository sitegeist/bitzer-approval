<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

#[Flow\Proxy(false)]
final class TabBox extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly TabBoxVariant $variant,
        public readonly Collection $tabs,
        public readonly ?string $identifier = null
    ) {
    }
}
