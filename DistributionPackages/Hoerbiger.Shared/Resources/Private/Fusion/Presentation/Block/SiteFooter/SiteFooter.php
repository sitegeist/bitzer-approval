<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLink;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class SiteFooter extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly UriInterface $siteUri,
        public readonly UriInterface $logoUri,
        public readonly Collection $primaryNavigationItems,
        public readonly Collection $secondaryNavigationItems,
        public readonly Collection $tertiaryNavigationItems,
        public readonly Collection $socialLinks,
        public readonly ?ContactFormLink $contactFormLink
    ) {
    }
}
