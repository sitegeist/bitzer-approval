<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class SearchPageIsInvalid extends \DomainException
{
    public static function becauseItMustBeGreaterZero(int $attemptedValue): self
    {
        return new self(
            'The given value "' . $attemptedValue . '" is no valid search page, must be greater than zero.',
            1633703830
        );
    }
}
