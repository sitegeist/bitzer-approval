<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum SeparatorVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_BOLD = 'bold';
    case VARIANT_BOLD_RED = 'boldRed';
    case VARIANT_FULL_WIDTH_WITH_CONTENT = 'fullWidthWithContent';
}
