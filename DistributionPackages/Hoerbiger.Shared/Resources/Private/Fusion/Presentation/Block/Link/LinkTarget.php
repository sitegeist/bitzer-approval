<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum LinkTarget: string
{
    use StringComponentVariant;

    case TARGET_SELF = '_self';
    case TARGET_BLANK = '_blank';

    public static function forLinkPropertyValue(string $linkPropertyValue): self
    {
        return \mb_substr($linkPropertyValue, 0, 7) === 'node://'
            ? self::TARGET_SELF
            : self::TARGET_BLANK;
    }

    public static function forNode(TraversableNodeInterface $node): self
    {
        if ($node->getNodeType()->isOfType('Neos.Neos:Shortcut')
            && $node->getProperty('targetMode') === 'selectedTarget'
            && \mb_strpos($node->getProperty('target'), 'http') === 0
        ) {
            return self::TARGET_BLANK;
        }

        return self::TARGET_SELF;
    }
}
