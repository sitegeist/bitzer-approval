<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NewsQuotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class NewsQuotation extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly NewsQuotationVariant $variant,
        public readonly SlotInterface $text,
        public readonly SlotInterface $spokenByCharacter,
        public readonly SlotInterface $division
    ) {
    }
}
