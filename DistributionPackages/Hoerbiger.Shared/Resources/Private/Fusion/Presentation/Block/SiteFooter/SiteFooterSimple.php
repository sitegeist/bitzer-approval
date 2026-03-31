<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class SiteFooterSimple extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly UriInterface $siteUri,
        public readonly UriInterface $logoUri,
    ) {
    }
}
