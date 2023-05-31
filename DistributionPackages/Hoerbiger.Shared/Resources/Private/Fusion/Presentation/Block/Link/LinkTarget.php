<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum LinkTarget: string
{
    case TARGET_SELF = '_self';
    case TARGET_BLANK = '_blank';


    public static function fromString(string $string): self
    {
        return match ($string){
            '_self' => self::TARGET_SELF,
            '_blank' => self::TARGET_BLANK,
            default => null
        };
    }

    public static function forLinkPropertyValue(string $linkPropertyValue): self
    {
        return \mb_substr($linkPropertyValue, 0, 7) === 'node://'
            ? self::TARGET_SELF
            : self::TARGET_BLANK;
    }

    public static function forNode(TraversableNodeInterface $node): self
    {
        if ($node->getNodeType()->isOfType('Neos.Neos:Shortcut')) {
            if ($node->getProperty('targetMode') === 'selectedTarget') {
                if (\mb_strpos($node->getProperty('target'), 'http') === 0) {
                    return self::TARGET_BLANK;
                }
            }
        }

        return self::TARGET_SELF;
    }

}
