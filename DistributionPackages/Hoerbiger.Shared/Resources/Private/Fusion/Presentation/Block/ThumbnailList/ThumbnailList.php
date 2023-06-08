<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ThumbnailList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class ThumbnailList extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ?Headline $headline,
        public readonly SlotInterface $thumbnailList
    ) {
    }
}
