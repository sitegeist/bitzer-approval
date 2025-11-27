<?php

/*
 * This file is part of the Nordmann.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\YouTubeVideo;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
enum YouTubeVideoAspectRatio: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case RATIO_16_9 = 'ratio_16_9';
    case RATIO_16_10 = 'ratio_16_10';
    case RATIO_4_3 = 'ratio_4_3';
}
