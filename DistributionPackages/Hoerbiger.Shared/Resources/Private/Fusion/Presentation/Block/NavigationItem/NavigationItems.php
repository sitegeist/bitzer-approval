<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\NavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,NavigationItemInterface>
 */
final class NavigationItems implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,NavigationItemInterface>|NavigationItemInterface[]
     */
    private array $navigationItems;

    /**
     * @param array<int,NavigationItemInterface> $array
     */
    public function __construct(array $array = [])
    {
        foreach ($array as $element) {
            if (!$element instanceof NavigationItemInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . NavigationItemInterface::class);
            }
        }
        $this->navigationItems = $array;
    }

    /**
     * @return \ArrayIterator<int,NavigationItemInterface>|NavigationItemInterface[]
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
