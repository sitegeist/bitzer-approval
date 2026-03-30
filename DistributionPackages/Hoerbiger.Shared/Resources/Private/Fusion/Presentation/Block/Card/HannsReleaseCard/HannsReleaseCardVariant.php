<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\HannsReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HannsReleaseCardVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_IMAGE_FIRST = 'imageFirst';
    case VARIANT_IMAGE_LAST = 'imageLast';
    case VARIANT_ALTERNATING = 'alternating';
}
