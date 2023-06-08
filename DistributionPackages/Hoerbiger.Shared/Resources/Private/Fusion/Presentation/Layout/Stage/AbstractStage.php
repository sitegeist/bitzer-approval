<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Text\Text;

#[Flow\Proxy(false)]
abstract class AbstractStage extends AbstractComponentPresentationObject
{
    public function __construct(
        private StageVariant $variant,
        private ?SlotInterface $metaline,
        private ?Headline $headline,
        private ?SlotInterface $subheadline,
        private ?Text $text,
        private ?SlotInterface $media,
        private ?SlotInterface $mediaBackground,
        private ?SlotInterface $button = null
    ) {
    }

    public function getVariant(): StageVariant
    {
        return $this->variant;
    }
    public function getMetaline(): ?SlotInterface
    {
        return $this->metaline;
    }
    public function getHeadline(): ?Headline
    {
        return $this->headline;
    }
    public function getSubheadline(): ?SlotInterface
    {
        return $this->subheadline;
    }
    public function getText(): ?Text
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
    public function getButton(): ?SlotInterface
    {
        return $this->button;
    }
}
