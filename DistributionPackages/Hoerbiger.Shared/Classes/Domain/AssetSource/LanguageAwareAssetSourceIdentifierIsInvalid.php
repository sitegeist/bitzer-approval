<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class LanguageAwareAssetSourceIdentifierIsInvalid extends \DomainException
{
    public static function becauseItMustBeOrStartWithNeos(string $attemptedValue): self
    {
        return new self(
            'Given value "' . $attemptedValue
                . '" is no valid asset source identifier, must be "neos" or start with "neos-"',
            1633532111
        );
    }
}
