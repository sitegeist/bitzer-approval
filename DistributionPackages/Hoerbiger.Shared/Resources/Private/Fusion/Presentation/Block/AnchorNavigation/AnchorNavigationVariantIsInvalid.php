<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\AnchorNavigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class AnchorNavigationVariantIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('The given value "' . $attemptedValue . '" is no valid AnchorNavigationVariant, must be one of the defined constants. ', 1678289569);
    }
}
