<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\CookieDeclaration;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class CookieDeclaration extends AbstractComponentPresentationObject
{
    /**
     * @param array<string,string> $labels
     */
    public function __construct(
        public readonly array $labels,
    ) {
    }
}
