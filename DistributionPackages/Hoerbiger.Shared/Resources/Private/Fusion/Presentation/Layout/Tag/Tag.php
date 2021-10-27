<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Tag;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Tag extends AbstractComponentPresentationObject implements TagInterface
{
    private string $tagName;

    private SlotInterface $content;

    public function __construct(
        string $tagName,
        SlotInterface $content
    ) {
        $this->tagName = $tagName;
        $this->content = $content;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
