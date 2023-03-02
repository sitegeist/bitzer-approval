<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

interface SiteHeaderInterface extends ComponentPresentationObjectInterface
{
    public function getSiteUri(): UriInterface;

    public function getMainNavigation(): ?NavigationItems;

    public function getLanguageNavigation(): NavigationItems;

    public function getRootLevelIcon(): Icon;

    public function getSubLevelIcon(): Icon;

    public function getSearchTargetUri(): UriInterface;

    public function getSearchIcon(): IconInterface;

    public function getLabels(): string;
}
