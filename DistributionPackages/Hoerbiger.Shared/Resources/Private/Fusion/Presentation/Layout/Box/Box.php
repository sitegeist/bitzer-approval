<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Box extends AbstractComponentPresentationObject implements BoxInterface
{
    private BoxVariant $variant;

    private SlotInterface $content;

    private ?string $identifier;

    public function __construct(
        BoxVariant $variant,
        SlotInterface $content,
        ?string $identifier = null
    ) {
        $this->variant = $variant;
        $this->content = $content;
        $this->identifier = $identifier;
    }

    public function getVariant(): BoxVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}
