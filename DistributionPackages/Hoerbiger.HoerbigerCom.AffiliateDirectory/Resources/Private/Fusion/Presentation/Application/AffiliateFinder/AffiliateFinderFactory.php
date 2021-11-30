<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class AffiliateFinderFactory extends AbstractComponentPresentationObjectFactory
{

    public function create(): AffiliateFinderInterface
    {
        return new AffiliateFinder(
            new Affiliates([Affiliate::dummy()]),
            'AIzaSyDu4LZZnG6K5U5Yx-BrkLfLD4jxIi474aM',
            []
        );
    }
}
