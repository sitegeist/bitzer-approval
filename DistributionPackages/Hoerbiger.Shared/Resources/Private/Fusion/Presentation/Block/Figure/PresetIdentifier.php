<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum PresetIdentifier: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case IDENTIFIER_XS = 'xs';
    case IDENTIFIER_SM = 'sm';
    case IDENTIFIER_MD = 'md';
    case IDENTIFIER_LG = 'lg';
    case IDENTIFIER_XL = 'xl';
    case IDENTIFIER_PDF = 'pdf';
}
