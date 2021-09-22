<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface TextInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): TextVariant;

    public function getContent(): SlotInterface;
}
