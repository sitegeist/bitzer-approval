<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Timeline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CollectionInterface;

/**
 * @Flow\Proxy(false)
 */
final class Timeline extends AbstractComponentPresentationObject implements TimelineInterface
{
    private CollectionInterface $milestones;

    public function __construct(
        CollectionInterface $milestones
    ) {
        $this->milestones = $milestones;
    }

    public function getMilestones(): CollectionInterface
    {
        return $this->milestones;
    }
}
