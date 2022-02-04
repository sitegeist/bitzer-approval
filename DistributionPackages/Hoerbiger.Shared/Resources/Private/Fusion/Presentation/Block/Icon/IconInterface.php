<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface IconInterface extends ComponentPresentationObjectInterface
{
    public function getCollectionName(): string;

    public function getIconName(): string;

    public function getSize(): ?IconSize;

    public function getColor(): ?IconColor;
}
