<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

use Neos\Flow\Annotations as Flow;

/**
 * The affiliate domain entity collection
 *
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,Affiliate>
 */
final class Affiliates implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var array<int,Affiliate>
     */
    private array $affiliates;

    /**
     * @param array<int,mixed> $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            if (!$item instanceof Affiliate) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . Affiliate::class);
            }
        }
        $this->affiliates = $items;
    }

    /**
     * @return \ArrayIterator<int,Affiliate>|Affiliate[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->affiliates);
    }

    /**
     * @return array<int,Affiliate>
     */
    public function toArray(): array
    {
        return $this->affiliates;
    }

    public function count(): int
    {
        return count($this->affiliates);
    }

    /**
     * @return array<int,Affiliate>
     */
    public function jsonSerialize(): array
    {
        return $this->affiliates;
    }
}
