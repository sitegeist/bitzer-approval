<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Teaser;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum Layout: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_ONE_COLUMN = 'oneColumn';
    case VALUE_TWO_COLUMNS = 'twoColumns';
    case VALUE_THREE_COLUMNS = 'threeColumns';
}

