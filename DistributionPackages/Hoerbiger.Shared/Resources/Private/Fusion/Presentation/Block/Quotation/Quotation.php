<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Quotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Quotation extends AbstractComponentPresentationObject implements QuotationInterface
{
    private QuotationVariant $variant;

    private SlotInterface $text;

    private SlotInterface $spokenByCharacter;

    public function __construct(
        QuotationVariant $variant,
        SlotInterface $text,
        SlotInterface $spokenByCharacter
    ) {
        $this->variant = $variant;
        $this->text = $text;
        $this->spokenByCharacter = $spokenByCharacter;
    }

    public function getVariant(): QuotationVariant
    {
        return $this->variant;
    }

    public function getText(): SlotInterface
    {
        return $this->text;
    }

    public function getSpokenByCharacter(): SlotInterface
    {
        return $this->spokenByCharacter;
    }
}
