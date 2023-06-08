<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class SiteHeader extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly SiteHeaderVariant $variant,
        public readonly UriInterface $siteUri,
        public readonly ?NavigationItems $mainNavigation,
        public readonly NavigationItems $languageNavigation,
        public readonly Icon $rootLevelIcon,
        public readonly Icon $subLevelIcon,
        public readonly UriInterface $searchTargetUri,
        public readonly Icon $searchIcon,
        public readonly string $labels
    ) {

    }
}
