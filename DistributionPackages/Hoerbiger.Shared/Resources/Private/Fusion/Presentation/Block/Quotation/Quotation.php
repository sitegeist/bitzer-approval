<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Quotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Quotation extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly QuotationVariant $variant,
        public readonly SlotInterface $text,
        public readonly SlotInterface $spokenByCharacter
    ) {
    }
}
