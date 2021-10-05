<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class LinkTarget implements PseudoEnumInterface
{
    const TARGET_SELF = '_self';
    const TARGET_BLANK = '_blank';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::TARGET_SELF
                && $string !== self::TARGET_BLANK) {
                throw LinkTargetIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function forLinkPropertyValue(string $linkPropertyValue): self
    {
        return \mb_substr($linkPropertyValue, 0, 7) === 'node://'
            ? LinkTarget::self()
            : LinkTarget::blank();
    }

    public static function forNode(TraversableNodeInterface $node): self
    {
        if ($node->getNodeType()->isOfType('Neos.Neos:Shortcut')) {
            if ($node->getProperty('targetMode') === 'selectedTarget') {
                if (\mb_strpos($node->getProperty('target'), 'http') === 0) {
                    return self::blank();
                }
            }
        }

        return self::self();
    }

    public static function self(): self
    {
        return self::from(self::TARGET_SELF);
    }

    public static function blank(): self
    {
        return self::from(self::TARGET_BLANK);
    }

    public function getIsSelf(): bool
    {
        return $this->value === self::TARGET_SELF;
    }

    public function getIsBlank(): bool
    {
        return $this->value === self::TARGET_BLANK;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TARGET_SELF),
            self::from(self::TARGET_BLANK)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
