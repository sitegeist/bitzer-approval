<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ContactFormLink;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class ContactFormLink extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SlotInterface $content,
        public readonly Button $button
    ) {
    }
}
