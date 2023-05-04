<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NewsQuotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class NewsQuotation extends AbstractComponentPresentationObject implements NewsQuotationInterface
{
    private NewsQuotationVariant $variant;

    private SlotInterface $text;

    private SlotInterface $spokenByCharacter;

    private SlotInterface $division;

    public function __construct(
        NewsQuotationVariant $variant,
        SlotInterface $text,
        SlotInterface $spokenByCharacter,
        SlotInterface $division
    ) {
        $this->variant = $variant;
        $this->text = $text;
        $this->spokenByCharacter = $spokenByCharacter;
        $this->division = $division;
    }

    public function getVariant(): NewsQuotationVariant
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

    public function getDivision(): SlotInterface
    {
        return $this->division;
    }
}
