<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Row;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;

/**
 * @Flow\Proxy(false)
 */
final class Row extends AbstractComponentPresentationObject implements RowInterface
{
    private RowVariant $variant;

    private Collection $content;

    public function __construct(
        RowVariant $variant,
        Collection $content
    ) {
        $this->variant = $variant;
        $this->content = $content;
    }

    public function getVariant(): RowVariant
    {
        return $this->variant;
    }

    public function getContent(): Collection
    {
        return $this->content;
    }
}
