<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class StageVariantIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('The given value "' . $attemptedValue . '" is no valid StageVariant, must be one of the defined constants. ', 1643104962);
    }
}
