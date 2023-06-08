<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Video\Video;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class SliderItem extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ?Headline $headline,
        public readonly ?SlotInterface $text,
        public readonly Figure $figure,
        public readonly Video $video
    ) {
    }
}
