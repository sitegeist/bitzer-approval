<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Application\PaybackCalculatorEVCP;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class PaybackCalculatorEVCP extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ?Headline $headline,
        public readonly string $applicationData
    ) {
    }
}
