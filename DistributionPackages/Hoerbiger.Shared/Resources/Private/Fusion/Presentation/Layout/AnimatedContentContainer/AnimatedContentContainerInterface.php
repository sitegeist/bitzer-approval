<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface AnimatedContentContainerInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): AnimatedContentContainerVariant;

    public function getContent(): SlotInterface;

    public function getVisibleStateClass(): string;

    public function getInVisibleStateClass(): string;
}
