<?php
declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum PressVariant: string
{
    case VARIANT_NEWS = 'news';
    case VARIANT_HANNS = 'hanns';
    case VARIANT_YEARBOOK = 'yearbook';

    public static function fromString(string $string): self
    {
        return match ($string){
            'news' => self::VARIANT_NEWS,
            'hanns' => self::VARIANT_HANNS,
            'yearbook' => self::VARIANT_YEARBOOK,
            default => null
        };
    }

    public static function fromNode(NodeInterface $node): self|null
    {
        return match ( (string)$node->getNodeType() ){
            'news' => self::VARIANT_NEWS,
            'hanns' => self::VARIANT_HANNS,
            'yearbook' => self::VARIANT_YEARBOOK,
            default => null
        };
    }
}
