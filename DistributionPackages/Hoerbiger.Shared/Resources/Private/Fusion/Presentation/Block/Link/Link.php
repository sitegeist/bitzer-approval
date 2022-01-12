<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Link extends AbstractComponentPresentationObject implements LinkInterface
{
    private LinkVariant $variant;

    private UriInterface $href;

    private LinkTarget $target;

    private ?string $rel;

    private ?SlotInterface $content;

    private ?string $ariaLabel;

    public function __construct(
        LinkVariant $variant,
        UriInterface $href,
        LinkTarget $target,
        ?string $rel,
        ?SlotInterface $content,
        ?string $ariaLabel
    ) {
        $this->variant = $variant;
        $this->href = $href;
        $this->target = $target;
        $this->rel = $rel;
        $this->content = $content;
        $this->ariaLabel = $ariaLabel;
    }

    public function getVariant(): LinkVariant
    {
        return $this->variant;
    }

    public function getHref(): UriInterface
    {
        return $this->href;
    }

    public function getTarget(): LinkTarget
    {
        return $this->target;
    }

    public function getRel(): ?string
    {
        return $this->rel;
    }

    public function getContent(): ?SlotInterface
    {
        return $this->content;
    }

    public function getAriaLabel(): ?string
    {
        return $this->ariaLabel;
    }

    public function withContent(?SlotInterface $content): self
    {
        return new self(
            $this->variant,
            $this->href,
            $this->target,
            $this->rel,
            $content,
            $this->ariaLabel
        );
    }

    public function asContainer(): self
    {
        return $this->withLinkVariant(LinkVariant::container());
    }

    public function forLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::label());
    }

    public function asPlainLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::plainLabel());
    }

    public function asIconLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::iconLabel());
    }

    public function withLinkVariant(LinkVariant $variant): self
    {
        return new self(
            $variant,
            $this->href,
            $this->target,
            $this->rel,
            $this->content,
            $this->ariaLabel
        );
    }

    public function asBlankTargetLink(): self
    {
        return new self(
            $this->variant,
            $this->href,
            LinkTarget::blank(),
            $this->rel,
            $this->content,
            $this->ariaLabel
        );
    }
}
