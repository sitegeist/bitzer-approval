<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\TabBox;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class TabBox extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly TabBoxVariant $variant,
        public readonly ?Headline $headline,
        public readonly Collection $tabs,
        public readonly ?string $identifier = null
    ) {
    }
}
