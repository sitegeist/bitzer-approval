<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MarketingUrl;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if a path is already occupied
 *
 * @Flow\Proxy(false)
 */
final class PathIsAlreadyOccupied extends \DomainException
{
    public static function create(string $attemptedPath): self
    {
        return new self('Path ' . $attemptedPath . ' is already occupied.', 1662478782);
    }
}
