<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\VideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\AbstractStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class VideoStage extends AbstractStage
{
    private Uri $desktopVideoUri;

    private Uri $mobileVideoUri;

    public function __construct(
        StageVariant $variant,
        ?SlotInterface $metaline,
        ?HeadlineInterface $headline,
        ?SlotInterface $subheadline,
        ?TextInterface $text,
        ?SlotInterface $media,
        ?SlotInterface $mediaBackground,
        ?SlotInterface $button,
        Uri $desktopVideoUri,
        Uri $mobileVideoUri
    ) {
        $this->desktopVideoUri = $desktopVideoUri;
        $this->mobileVideoUri = $mobileVideoUri;

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

    public function getDesktopVideoUri(): Uri
    {
        return $this->desktopVideoUri;
    }

    public function getMobileVideoUri(): Uri
    {
        return $this->mobileVideoUri;
    }
}
