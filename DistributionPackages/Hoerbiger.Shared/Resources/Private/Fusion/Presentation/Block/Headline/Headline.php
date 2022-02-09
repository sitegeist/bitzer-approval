<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Headline extends AbstractComponentPresentationObject implements HeadlineInterface
{
    private HeadlineSize $size;

    private HeadlineColor $color;

    private HeadlineType $type;

    private SlotInterface $content;

    public function __construct(
        HeadlineSize $size,
        HeadlineColor $color,
        HeadlineType $type,
        SlotInterface $content
    ) {
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->content = $content;
    }

    public function getSize(): HeadlineSize
    {
        return $this->size;
    }

    public function getColor(): HeadlineColor
    {
        return $this->color;
    }

    public function getType(): HeadlineType
    {
        return $this->type;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
