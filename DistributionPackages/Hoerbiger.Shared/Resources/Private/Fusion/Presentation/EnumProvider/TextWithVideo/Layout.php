<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\TextWithVideo;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum Layout: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_VIDEOFIRST = 'videoFirst';
    case VALUE_VIDEOLAST = 'videoLast';
}
