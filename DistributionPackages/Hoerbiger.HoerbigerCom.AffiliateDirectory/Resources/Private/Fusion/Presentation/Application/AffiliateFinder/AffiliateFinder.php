<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class AffiliateFinder extends AbstractComponentPresentationObject
{
    /**
     * @param array<string,string> $labels
     */
    public function __construct(
        public readonly Affiliates $affiliates,
        public readonly string $googleMapsApiKey,
        /** @var array<string,string> */
        public readonly array $labels,
        public readonly string $language
    ) {
    }

    public function getApplicationData(): string
    {
        return json_encode(
            [
                'googleMapsApiKey' => $this->googleMapsApiKey,
                'affiliates' => $this->affiliates,
                'labels' => $this->labels,
                'language' => $this->language
            ]
        )?: '{}';
    }
}
