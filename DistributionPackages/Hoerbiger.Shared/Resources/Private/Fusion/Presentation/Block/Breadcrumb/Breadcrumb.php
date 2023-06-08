<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Breadcrumb;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;

#[Flow\Proxy(false)]
final class Breadcrumb extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $links,
        public readonly SlotInterface $currentDocumentItem,
        public readonly Icon $glue,
        public readonly Icon $compactGlue
    ) {
    }
}
