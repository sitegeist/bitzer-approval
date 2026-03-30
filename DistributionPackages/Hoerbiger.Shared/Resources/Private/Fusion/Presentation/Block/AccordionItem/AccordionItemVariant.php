<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\AccordionItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum AccordionItemVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_HANNS_LIST = 'hannsList';
}
