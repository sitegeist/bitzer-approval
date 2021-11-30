<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface AffiliateFinderInterface extends ComponentPresentationObjectInterface
{
    public function getApplicationData(): string;
}
