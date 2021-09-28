<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class Button extends AbstractComponentPresentationObject implements ButtonInterface
{
    private ButtonType $type;

    private ButtonVariant $variant;

    private SlotInterface $content;

    private ?UriInterface $href;

    private ?string $target;

    private ?string $rel;

    public function __construct(
        ButtonType $type,
        ButtonVariant $variant,
        SlotInterface $content,
        ?UriInterface $href,
        ?string $target,
        ?string $rel
    ) {
        $this->type = $type;
        $this->variant = $variant;
        $this->content = $content;
        $this->href = $href;
        $this->target = $target;
        $this->rel = $rel;
    }

    public function getType(): ButtonType
    {
        return $this->type;
    }

    public function getVariant(): ButtonVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getHref(): ?UriInterface
    {
        return $this->href;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function getRel(): ?string
    {
        return $this->rel;
    }
}
