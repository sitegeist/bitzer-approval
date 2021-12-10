<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Application\CompactSearchForm;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

interface CompactSearchFormInterface extends ComponentPresentationObjectInterface
{
    public function getTargetUri(): UriInterface;

    public function getSearchIcon(): IconInterface;
}
