<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class CustomerGroupIdentifierIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self(
            'The given value "' . $attemptedValue
                . '" is no valid CustomerGroupIdentifier, must be one of the defined constants.',
            1637076414
        );
    }
}
