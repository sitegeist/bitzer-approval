<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum Columns: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_33_66 = '33_66';
    case VALUE_50_50 = '50_50';
    case VALUE_66_33 = '66_33';
}
