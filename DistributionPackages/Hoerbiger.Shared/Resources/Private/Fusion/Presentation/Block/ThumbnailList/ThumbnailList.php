<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ThumbnailList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class ThumbnailList extends AbstractComponentPresentationObject implements ThumbnailListInterface
{
    private ?HeadlineInterface $headline;

    private SlotInterface $thumbnailList;

    public function __construct(
        ?HeadlineInterface $headline,
        SlotInterface $thumbnailList
    ) {
        $this->headline = $headline;
        $this->thumbnailList = $thumbnailList;
    }

    public function getHeadline(): ?HeadlineInterface
    {
        return $this->headline;
    }

    public function getThumbnailList(): SlotInterface
    {
        return $this->thumbnailList;
    }
}
