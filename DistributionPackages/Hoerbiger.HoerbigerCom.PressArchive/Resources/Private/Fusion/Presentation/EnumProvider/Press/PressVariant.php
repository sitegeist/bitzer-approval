<?php
declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;

enum PressVariant: string
{
    case VARIANT_NEWS = 'news';
    case VARIANT_HANNS = 'hanns';
    case VARIANT_YEARBOOK = 'yearbook';

    public static function tryFromNode(NodeInterface $node): ?self
    {
        return match ($node->getNodeType()->getName()) {
            'Hoerbiger.HoerbigerCom.PressArchive:Document.PressRelease' => self::VARIANT_NEWS,
            'Hoerbiger.HoerbigerCom.PressArchive:Document.HannsRelease' => self::VARIANT_HANNS,
            'Hoerbiger.HoerbigerCom.PressArchive:Document.YearbookRelease' => self::VARIANT_YEARBOOK,
            default => null
        };
    }
}
