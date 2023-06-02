<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum Columns:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_MAX_ONE = 'maxOne';
    case VALUE_MAX_TWO = 'maxTwo';
    case VALUE_MAX_ONE_LEFT = 'maxOneLeft';
}
