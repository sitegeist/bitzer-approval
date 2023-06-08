<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
abstract class AbstractCard extends AbstractComponentPresentationObject
{
    public function __construct(
        private ?SlotInterface $media,
        private ?SlotInterface $header,
        private ?SlotInterface $subheader,
        private ?SlotInterface $supportingText,
        private ?SlotInterface $buttons
    ) {
    }
    public function getMedia(): ?SlotInterface
    {
        return $this->media;
    }

    public function getHeader(): ?SlotInterface
    {
        return $this->header;
    }

    public function getSubheader(): ?SlotInterface
    {
        return $this->subheader;
    }

    public function getSupportingText(): ?SlotInterface
    {
        return $this->supportingText;
    }
    public function getButtons(): ?SlotInterface
    {
        return $this->buttons;
    }
}
