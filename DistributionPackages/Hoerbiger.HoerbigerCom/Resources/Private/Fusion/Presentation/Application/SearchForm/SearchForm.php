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

    public function __construct(
        UriInterface $endpointUri
    ) {
        $this->endpointUri = $endpointUri;
    }

    public function getEndpointUri(): UriInterface
    {
        return $this->endpointUri;
    }
}
