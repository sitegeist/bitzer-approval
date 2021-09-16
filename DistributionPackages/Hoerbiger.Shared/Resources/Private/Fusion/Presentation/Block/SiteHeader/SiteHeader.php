<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class SiteHeader extends AbstractComponentPresentationObject implements SiteHeaderInterface
{
    public function __construct(
    ) {
    }
}
