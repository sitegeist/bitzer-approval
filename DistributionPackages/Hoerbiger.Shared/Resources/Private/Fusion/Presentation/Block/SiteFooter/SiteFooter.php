<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class SiteFooter extends AbstractComponentPresentationObject implements SiteFooterInterface
{
    public function __construct(
    ) {
    }
}
