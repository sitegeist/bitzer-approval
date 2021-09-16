<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\IntroSection\IntroSectionVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;

/**
 * @Flow\Proxy(false)
 */
final class IntroSection extends AbstractComponentPresentationObject implements IntroSectionInterface
{
    private IntroSectionVariant $variant;

    private HeadlineInterface $headline;

    private ?SlotInterface $subheadline;

    private TextInterface $text;

    private SlotInterface $media;

    public function __construct(
        IntroSectionVariant $variant,
        HeadlineInterface $headline,
        ?SlotInterface $subheadline,
        TextInterface $text,
        SlotInterface $media
    ) {
        $this->variant = $variant;
        $this->headline = $headline;
        $this->subheadline = $subheadline;
        $this->text = $text;
        $this->media = $media;
    }

    public function getVariant(): IntroSectionVariant
    {
        return $this->variant;
    }

    public function getHeadline(): HeadlineInterface
    {
        return $this->headline;
    }

    public function getSubheadline(): ?SlotInterface
    {
        return $this->subheadline;
    }

    public function getText(): TextInterface
    {
        return $this->text;
    }

    public function getMedia(): SlotInterface
    {
        return $this->media;
    }
}
