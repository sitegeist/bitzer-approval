<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MetaHeadline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class MetaHeadline extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly MetaHeadlineVariant $variant,
        public readonly ?SlotInterface $slice1,
        public readonly ?SlotInterface $slice2,
        public readonly ?SlotInterface $slice3
    ) {
    }
}
