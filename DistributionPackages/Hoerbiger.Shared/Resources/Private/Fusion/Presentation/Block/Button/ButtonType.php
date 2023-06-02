<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum ButtonType: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case TYPE_REGULAR = 'regular';
    case TYPE_LINK = 'link';
}
