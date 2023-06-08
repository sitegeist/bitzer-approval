<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Application\CompactSearchForm;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class CompactSearchForm extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly UriInterface $targetUri,
        public readonly Icon $searchIcon,
        public readonly string $labels
    ) {
    }
}
