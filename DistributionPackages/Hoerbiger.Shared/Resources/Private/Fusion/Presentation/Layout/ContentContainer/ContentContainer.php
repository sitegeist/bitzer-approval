<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\ContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class ContentContainer extends AbstractComponentPresentationObject implements ContentContainerInterface
{
    private ContentContainerVariant $variant;

    private SlotInterface $content;

    public function __construct(
        ContentContainerVariant $variant,
        SlotInterface $content
    ) {
        $this->variant = $variant;
        $this->content = $content;
    }

    public function getVariant(): ContentContainerVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
