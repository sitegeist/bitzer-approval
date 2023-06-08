<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class Link extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly LinkVariant $variant,
        public readonly UriInterface $href,
        public readonly LinkTarget $target,
        public readonly ?string $rel,
        public readonly ?SlotInterface $content,
        public readonly ?string $ariaLabel
    ) {
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
        return $this->withLinkVariant(LinkVariant::VARIANT_CONTAINER);
    }

    public function forLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::VARIANT_LABEL);
    }

    public function asPlainLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::VARIANT_PLAINLABEL);
    }

    public function asIconLabel(): self
    {
        return $this->withLinkVariant(LinkVariant::VARIANT_ICONLABEL);
    }

    public function asNoStyles(): self
    {
        return $this->withLinkVariant(LinkVariant::VARIANT_NOSTYLES);
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
            LinkTarget::TARGET_SELF,
            $this->rel,
            $this->content,
            $this->ariaLabel
        );
    }
}
