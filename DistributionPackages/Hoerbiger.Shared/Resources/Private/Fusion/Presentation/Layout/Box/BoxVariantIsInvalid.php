<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class BoxVariantIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('The given value "' . $attemptedValue . '" is no valid BoxVariant, must be one of the defined constants. ', 1632810759);
    }
}
