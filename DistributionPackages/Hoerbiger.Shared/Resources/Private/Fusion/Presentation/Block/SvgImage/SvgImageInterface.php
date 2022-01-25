<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SvgImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

interface SvgImageInterface extends ComponentPresentationObjectInterface
{
    public function getSource(): UriInterface;

    public function getAlt(): string;

    public function getTitle(): string;
}
