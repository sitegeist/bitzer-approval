<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;

interface SeparatorInterface extends ComponentPresentationObjectInterface
{
    public function getVariant(): SeparatorVariant;
}
