<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class LanguageBasedRoleIsInvalid extends \DomainException
{
    public static function becauseItMustContainOfTheDefinedPresetLabels(string $presetLabel): self
    {
        return new self(
            'The given preset label "' . $presetLabel
                . '" is not set in the content dimension configuration.',
            1701874169
        );
    }
}
