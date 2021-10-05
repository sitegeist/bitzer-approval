<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Integration\CacheSegment;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class CacheSegment extends AbstractComponentPresentationObject implements CacheSegmentInterface
{
    private SlotInterface $content;

    private string $prototypeName;

    public function __construct(
        SlotInterface $content,
        string $prototypeName
    ) {
        $this->content = $content;
        $this->prototypeName = $prototypeName;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getPrototypeName(): string
    {
        return $this->prototypeName;
    }
}
