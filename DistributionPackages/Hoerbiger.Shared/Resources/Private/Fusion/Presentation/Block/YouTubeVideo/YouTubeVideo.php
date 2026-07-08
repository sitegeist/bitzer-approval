<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\YouTubeVideo;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class YouTubeVideo extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly YouTubeVideoAspectRatio $aspectRatio,
        public readonly UriInterface $source,
        public readonly ?Figure $figure,
        public readonly ?Icon $playIcon,
        public readonly ?bool $centered = false
    ) {
    }
}
