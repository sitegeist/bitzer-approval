<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PeopleStory;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Quotation\Quotation;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class PeopleStory extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly Figure $figure,
        public readonly Headline $headline,
        public readonly ?Text $text,
        public readonly ?Quotation $quotation,
        public readonly ?SlotInterface $metaline,
        public readonly ?SlotInterface $tags
    ) {
    }
}
