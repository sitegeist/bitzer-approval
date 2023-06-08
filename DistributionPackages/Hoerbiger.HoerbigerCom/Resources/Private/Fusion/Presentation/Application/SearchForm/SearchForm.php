<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class SearchForm extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $csrfProtectionToken,
        public readonly UriInterface $endpointUri,
        public readonly string $sortingPropertyOptions,
        public readonly string $periodOptions,
        public readonly string $labels,
        public readonly string $language,
        public readonly UriInterface $mostWantedEndpointUri
    ) {
    }
    public function getApplicationData(): string
    {
        return json_encode([
            'csrfProtectionToken' => $this->csrfProtectionToken,
            'endpointUri' => (string)$this->endpointUri,
            'sortingPropertyOptions' => json_decode($this->sortingPropertyOptions),
            'periodOptions' => json_decode($this->periodOptions),
            'labels' => json_decode($this->labels),
            'language' => $this->language,
            'mostWantedEndpointUri' => (string)$this->mostWantedEndpointUri
        ]) ?: '{}';
    }
}
