<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class ListItem extends AbstractComponentPresentationObject implements ListItemInterface
{
    private ListItemVariant $variant;

    private SlotInterface $term;

    private SlotInterface $definition;

    public function __construct(
        ListItemVariant $variant,
        SlotInterface $term,
        SlotInterface $definition
    ) {
        $this->variant = $variant;
        $this->term = $term;
        $this->definition = $definition;
    }

    public function getVariant(): ListItemVariant
    {
        return $this->variant;
    }

    public function getTerm(): SlotInterface
    {
        return $this->term;
    }

    public function getDefinition(): SlotInterface
    {
        return $this->definition;
    }
}
