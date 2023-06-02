<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MarketingUrl;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * A collection of marketing URLs
 *
 * @implements \IteratorAggregate<int|string,MarketingUrl>
 * @Flow\Proxy(false)
 */
final class MarketingUrls implements \IteratorAggregate
{
    /**
     * @var array<int|string,MarketingUrl>
     */
    private array $marketingUrls;

    public function __construct(MarketingUrl ...$marketingUrls)
    {
        $this->marketingUrls = $marketingUrls;
    }

    /**
     * @return \ArrayIterator<int|string,MarketingUrl>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->marketingUrls);
    }
}
