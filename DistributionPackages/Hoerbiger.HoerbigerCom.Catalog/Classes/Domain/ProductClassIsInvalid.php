<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class ProductClassIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self(
            'The given value "' . $attemptedValue . '" is no valid ProductClass, must be one of the defined constants.',
            1643103108
        );
    }
}
