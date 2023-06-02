<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum FigureObjectFit: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case FIT_COVER = 'cover';
    case FIT_CONTAIN = 'contain';
}
