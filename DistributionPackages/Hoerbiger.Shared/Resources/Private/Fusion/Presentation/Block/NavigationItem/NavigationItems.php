<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,NavigationItem>
 */
final class NavigationItems implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,NavigationItem>|NavigationItem[]
     */
    private array $navigationItems;

    /**
     * @param array<int,NavigationItem> $array
     */
    public function __construct(array $array = [])
    {
        foreach ($array as $element) {
            if (!$element instanceof NavigationItem) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . NavigationItem::class);
            }
        }
        $this->navigationItems = $array;
    }

    /**
     * @return \ArrayIterator<int,NavigationItem>|NavigationItem[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->navigationItems);
    }

    public function count(): int
    {
        return count($this->navigationItems);
    }
}
