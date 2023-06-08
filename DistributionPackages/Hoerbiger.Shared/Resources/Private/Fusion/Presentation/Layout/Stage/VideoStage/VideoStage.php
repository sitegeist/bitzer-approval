<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\VideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Layout\Stage\AbstractStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class VideoStage extends AbstractStage
{
    public function __construct(
        public readonly StageVariant $variant,
        public readonly ?SlotInterface $metaline,
        public readonly ?Headline $headline,
        public readonly ?SlotInterface $subheadline,
        public readonly ?Text $text,
        public readonly ?SlotInterface $media,
        public readonly ?SlotInterface $mediaBackground,
        public readonly ?SlotInterface $button,
        public readonly Uri $desktopVideoUri,
        public readonly Figure $mobileFigure
    ) {
        parent::__construct(
            $variant,
            $metaline,
            $headline,
            $subheadline,
            $text,
            $media,
            $mediaBackground,
            $button
        );
    }
}
