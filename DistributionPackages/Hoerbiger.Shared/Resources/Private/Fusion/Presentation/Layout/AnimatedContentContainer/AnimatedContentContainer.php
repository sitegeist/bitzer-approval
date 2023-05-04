<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

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
final class AnimatedContentContainer extends AbstractComponentPresentationObject implements AnimatedContentContainerInterface
{
    private AnimatedContentContainerVariant $variant;

    private SlotInterface $content;

    private string $visibleStateClass;

    private string $invisibleStateClass;

    public function __construct(
        AnimatedContentContainerVariant $variant,
        SlotInterface $content,
        string  $visibleStateClass,
        string $invisibleStateClass
    ) {
        $this->variant = $variant;
        $this->content = $content;
        $this->visibleStateClass = $visibleStateClass;
        $this->invisibleStateClass = $invisibleStateClass;
    }

    public function getVariant(): AnimatedContentContainerVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getVisibleStateClass(): string
    {
        return $this->visibleStateClass;
    }

    public function getInvisibleStateClass(): string
    {
        return $this->invisibleStateClass;
    }
}
