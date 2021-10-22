<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;

/**
 * @Flow\Proxy(false)
 */
abstract class AbstractStage extends AbstractComponentPresentationObject implements StageInterface
{
    private StageVariant $variant;

    private ?HeadlineInterface $headline;

    private ?SlotInterface $subheadline;

    private ?TextInterface $text;

    private ?SlotInterface $media;

    private ?SlotInterface $mediaBackground;

    public function __construct(
        StageVariant $variant,
        ?HeadlineInterface $headline,
        ?SlotInterface $subheadline,
        ?TextInterface $text,
        ?SlotInterface $media,
        ?SlotInterface $mediaBackground
    ) {
        $this->variant = $variant;
        $this->headline = $headline;
        $this->subheadline = $subheadline;
        $this->text = $text;
        $this->media = $media;
        $this->mediaBackground = $mediaBackground;
    }

    public function getVariant(): StageVariant
    {
        return $this->variant;
    }

    public function getHeadline(): ?HeadlineInterface
    {
        return $this->headline;
    }

    public function getSubheadline(): ?SlotInterface
    {
        return $this->subheadline;
    }

    public function getText(): ?TextInterface
    {
        return $this->text;
    }

    public function getMedia(): ?SlotInterface
    {
        return $this->media;
    }

    public function getMediaBackground(): ?SlotInterface
    {
        return $this->mediaBackground;
    }
}
