<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class ListItem extends AbstractComponentPresentationObject implements ListItemInterface
{
    private SlotInterface $term;

    private SlotInterface $definition;

    public function __construct(
        SlotInterface $term,
        SlotInterface $definition
    ) {
        $this->term = $term;
        $this->definition = $definition;
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
