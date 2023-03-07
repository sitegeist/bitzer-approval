<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\AbstractCard;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class PressReleaseCard extends AbstractCard
{
    private PressReleaseCardVariant $variant;
    private ?SlotInterface $datePublished;
    public function __construct(
        PressReleaseCardVariant $variant,
        ?SlotInterface $datePublished,
        ?SlotInterface $media,
        ?SlotInterface $header,
        ?SlotInterface $subheader,
        ?SlotInterface $supportingText,
        ?SlotInterface $buttons
    ) {
        parent::__construct(
            $media,
            $header,
            $subheader,
            $supportingText,
            $buttons
        );
        $this->variant = $variant;
        $this->datePublished = $datePublished;
    }

    public function getVariant(): PressReleaseCardVariant
    {
        return $this->variant;
    }

    public function getDatePublished(): ?SlotInterface
    {
        return $this->datePublished;
    }
}
