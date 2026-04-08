<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Card\YearbookReleaseCard;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum YearbookReleaseCardVariant: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_SMALL = 'small';
    case VARIANT_BIG = 'big';

    case VARIANT_META = 'meta';
    case VARIANT_NEWS = 'news';
    case VARIANT_TOP = 'top';
    case VARIANT_TOP_COMPACT = 'topCompact';
    case VARIANT_TOP_NEWS = 'topNews';
    case VARIANT_HANNS = 'hanns';
    case VARIANT_YEARBOOK = 'yearbook';
}
