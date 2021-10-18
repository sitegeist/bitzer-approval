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
    private UriInterface $endpointUri;

    private string $sortingPropertyOptions;

    private string $periodOptions;

    private string $labels;

    public function __construct(
        UriInterface $endpointUri,
        string $sortingPropertyOptions,
        string $periodOptions,
        string $labels
    ) {
        $this->endpointUri = $endpointUri;
        $this->sortingPropertyOptions = $sortingPropertyOptions;
        $this->periodOptions = $periodOptions;
        $this->labels = $labels;
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
}
