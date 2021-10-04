<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class DefinitionList extends AbstractComponentPresentationObject implements DefinitionListInterface
{
    private DefinitionListVariant $variant;

    private SlotInterface $content;

    public function __construct(
        DefinitionListVariant $variant,
        SlotInterface $content
    ) {
        $this->variant = $variant;
        $this->content = $content;
    }

    public function getVariant(): DefinitionListVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
