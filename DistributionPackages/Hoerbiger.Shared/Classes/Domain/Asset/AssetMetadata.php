<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * The asset metadata domain value object
 *
 * @Flow\Proxy(false)
 */
final class AssetMetadata
{
    private \DateTimeImmutable $datePublished;

    public function __construct(\DateTimeImmutable $datePublished)
    {
        $this->datePublished = $datePublished;
    }

    public function getDatePublished(): \DateTimeImmutable
    {
        return $this->datePublished;
    }
}
