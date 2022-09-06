<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MarketingUrl;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * The command to delete a marketing URL
 *
 * @Flow\Proxy(false)
 */
final class DeleteMarketingUrl
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param array<string,string> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['path']
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
