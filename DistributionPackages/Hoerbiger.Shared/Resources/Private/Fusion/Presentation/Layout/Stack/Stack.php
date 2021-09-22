<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stack;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Stack extends AbstractComponentPresentationObject implements StackInterface
{
    private StackVariant $variant;

    private SlotInterface $content;

    public function __construct(
        StackVariant $variant,
        SlotInterface $content
    ) {
        $this->variant = $variant;
        $this->content = $content;
    }

    public function getVariant(): StackVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
