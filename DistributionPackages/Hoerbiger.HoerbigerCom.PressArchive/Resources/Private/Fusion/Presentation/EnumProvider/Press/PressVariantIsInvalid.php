<?php
declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class PressVariantIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('The given value "' . $attemptedValue . '" is no valid PressVariant, must be one of the defined constants. ', 1678190571);
    }
}
