<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HeadlineType:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case TYPE_H1 = 'h1';
    case TYPE_H2 = 'h2';
    case TYPE_DIV = 'div';
}
