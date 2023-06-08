<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SvgImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class SvgImage extends AbstractComponentPresentationObject
{

    public function __construct(
        public readonly UriInterface $source,
        public readonly string $alt,
        public readonly string $title
    ) {
    }
}
