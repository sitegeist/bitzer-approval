<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class PartialFigureEclipse extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly PartialFigureEclipseVariant $variant,
        public readonly Figure $figure,
        public readonly SlotInterface $content
    ) {
    }
}
