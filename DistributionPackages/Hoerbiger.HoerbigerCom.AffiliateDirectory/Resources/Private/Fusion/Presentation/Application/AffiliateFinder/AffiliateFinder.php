<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class AffiliateFinder extends AbstractComponentPresentationObject implements AffiliateFinderInterface
{
    private Affiliates $affiliates;
    private string $googleMapsApiKey;
    /**
     * @var array<string,string>
     */
    private array $labels;

    /**
     * @param array<string,string> $labels
     */
    public function __construct(
        Affiliates $affiliates,
        string $googleMapsApiKey,
        array $labels
    ) {
        $this->affiliates = $affiliates;
        $this->googleMapsApiKey = $googleMapsApiKey;
        $this->labels = $labels;
    }

    public function getApplicationData(): string
    {
        return json_encode(
            [
                'googleMapsApiKey' => $this->googleMapsApiKey,
                'affiliates' => $this->affiliates,
                'labels' => $this->labels
            ]
        )?: '{}';
    }
}
