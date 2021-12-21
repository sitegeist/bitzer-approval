<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class SearchForm extends AbstractComponentPresentationObject implements SearchFormInterface
{
    private string $csrfProtectionToken;

    private UriInterface $endpointUri;

    private string $sortingPropertyOptions;

    private string $periodOptions;

    private string $labels;

    private string $language;

    private UriInterface $mostWantedEndpointUri;

    public function __construct(
        string $csrfProtectionToken,
        UriInterface $endpointUri,
        string $sortingPropertyOptions,
        string $periodOptions,
        string $labels,
        string $language,
        UriInterface $mostWantedEndpointUri
    ) {
        $this->csrfProtectionToken = $csrfProtectionToken;
        $this->endpointUri = $endpointUri;
        $this->sortingPropertyOptions = $sortingPropertyOptions;
        $this->periodOptions = $periodOptions;
        $this->labels = $labels;
        $this->language = $language;
        $this->mostWantedEndpointUri = $mostWantedEndpointUri;
    }

    public function getEndpointUri(): UriInterface
    {
        return $this->endpointUri;
    }

    public function getSortingPropertyOptions(): string
    {
        return $this->sortingPropertyOptions;
    }

    public function getPeriodOptions(): string
    {
        return $this->periodOptions;
    }

    public function getLabels(): string
    {
        return $this->labels;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getMostWantedEndpointUri(): UriInterface
    {
        return $this->mostWantedEndpointUri;
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
