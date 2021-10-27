<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

/**
 * @Flow\Proxy(false)
 */
final class DefinitionList extends AbstractComponentPresentationObject implements DefinitionListInterface
{
    private DefinitionListVariant $variant;

    private Collection $definitions;

    public function __construct(
        DefinitionListVariant $variant,
        Collection $definitions
    ) {
        $this->variant = $variant;
        $this->definitions = $definitions;
    }

    public function getVariant(): DefinitionListVariant
    {
        return $this->variant;
    }

    public function getDefinitions(): Collection
    {
        return $this->definitions;
    }
}
