<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Events package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Events\Presentation\Application\EventFinder;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class EventFinder extends AbstractComponentPresentationObject
{
    public readonly string $applicationData;


    /**
     * @param array<string,string> $labels
     */
    public function __construct(
        string $locale,
        array $labels,
        UriInterface $endpointUri
    ) {
        $this->applicationData = \json_encode([
            'locale' => $locale,
            'labels' => $labels,
            'endpointUri' => (string)$endpointUri
        ], JSON_THROW_ON_ERROR);
    }
}
