<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\AbstractCard;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class PressReleaseCard extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly PressReleaseCardVariant $variant,
        public readonly ?SlotInterface $datePublished,
        public readonly ?SlotInterface $media,
        public readonly ?SlotInterface $header,
        public readonly ?SlotInterface $subheader,
        public readonly ?SlotInterface $supportingText,
        public readonly ?SlotInterface $buttons
    ) {
    }
}
