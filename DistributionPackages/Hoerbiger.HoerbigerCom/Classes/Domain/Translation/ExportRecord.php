<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
class ExportRecord
{
    public function __construct(
        public readonly ?UriInterface $uri,
        public readonly NodeAggregateIdentifier $nodeIdentifier,
        public readonly string $propertyName,
        public readonly string $contentInSourceLanguage,
        public readonly string $contentInTargetLanguage,
    ) {
    }
}
