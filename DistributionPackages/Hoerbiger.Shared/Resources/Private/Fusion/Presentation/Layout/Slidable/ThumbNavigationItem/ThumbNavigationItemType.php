<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum ThumbNavigationItemType:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case TYPE_DEFAULT = 'default';
    case TYPE_PLAINTEXT = 'plainText';
    case TYPE_MOBILE = 'mobile';
}
