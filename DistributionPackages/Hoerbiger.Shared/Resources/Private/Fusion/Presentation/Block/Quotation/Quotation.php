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
    private SlotInterface $text;

    private SlotInterface $spokenByCharacter;

    public function __construct(
        SlotInterface $text,
        SlotInterface $spokenByCharacter
    ) {
        $this->text = $text;
        $this->spokenByCharacter = $spokenByCharacter;
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
