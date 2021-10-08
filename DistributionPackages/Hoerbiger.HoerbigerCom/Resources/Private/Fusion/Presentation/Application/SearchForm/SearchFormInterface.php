<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

interface SearchFormInterface extends ComponentPresentationObjectInterface
{
    public function getEndpointUri(): UriInterface;
}
