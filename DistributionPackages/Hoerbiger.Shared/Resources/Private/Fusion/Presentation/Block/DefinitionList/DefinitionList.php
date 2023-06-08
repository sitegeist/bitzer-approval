<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

#[Flow\Proxy(false)]
final class DefinitionList extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly DefinitionListVariant $variant,
        public readonly Collection $definitions
    )
    {
    }
}

