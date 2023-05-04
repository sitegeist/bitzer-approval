<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class SiteHeader extends AbstractComponentPresentationObject implements SiteHeaderInterface
{
    private SiteHeaderVariant $variant;

    private UriInterface $siteUri;

    private ?NavigationItems $mainNavigation;

    private NavigationItems $languageNavigation;

    private Icon $rootLevelIcon;

    private Icon $subLevelIcon;

    private UriInterface $searchTargetUri;

    private IconInterface $searchIcon;

    private string $labels;

    public function __construct(
        SiteHeaderVariant $variant,
        UriInterface $siteUri,
        ?NavigationItems $mainNavigation,
        NavigationItems $languageNavigation,
        Icon $rootLevelIcon,
        Icon $subLevelIcon,
        UriInterface $searchTargetUri,
        IconInterface $searchIcon,
        string $labels
    ) {
        $this->variant = $variant;
        $this->siteUri = $siteUri;
        $this->mainNavigation = $mainNavigation;
        $this->languageNavigation = $languageNavigation;
        $this->rootLevelIcon = $rootLevelIcon;
        $this->subLevelIcon = $subLevelIcon;
        $this->searchTargetUri = $searchTargetUri;
        $this->searchIcon = $searchIcon;
        $this->labels = $labels;

    }

    public function getVariant(): SiteHeaderVariant
    {
        return $this->variant;
    }

    public function getSiteUri(): UriInterface
    {
        return $this->siteUri;
    }

    public function getMainNavigation(): ?NavigationItems
    {
        return $this->mainNavigation;
    }

    public function getLanguageNavigation(): NavigationItems
    {
        return $this->languageNavigation;
    }

    public function getRootLevelIcon(): Icon
    {
        return $this->rootLevelIcon;
    }

    public function getSubLevelIcon(): Icon
    {
        return $this->subLevelIcon;
    }

    public function getSearchTargetUri(): UriInterface
    {
        return $this->searchTargetUri;
    }

    public function getSearchIcon(): IconInterface
    {
        return $this->searchIcon;
    }

    public function getLabels(): string
    {
        return $this->labels;
    }

}
