<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MatomoOptOut;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

interface MatomoOptOutInterface extends ComponentPresentationObjectInterface
{
    public function getSrc(): UriInterface;
}
