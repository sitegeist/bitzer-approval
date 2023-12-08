<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

#[Flow\Proxy(false)]
final class LanguageBasedRoleId
{
    private const ROLE_ID_PREFIX = 'Hoerbiger.Shared:';
    private const ROLE_ID_SUFFIX = 'Editor';
    public function __construct(
        private readonly string $value
    ) {
    }

    public static function tryFromUser(?User $user): ?self
    {
        $roleId = null;
        foreach ($user?->getAccounts() ?: [] as $account) {
            foreach ($account->getRoles() as $role) {
                $roleId = self::tryFromRoleId($role->getIdentifier());
                if (!$roleId) {
                    return null;
                }
            }
        }
        return $roleId;
    }
    public static function tryFromRoleId(string $roleId): ?self
    {
        if (\str_starts_with($roleId, self::ROLE_ID_PREFIX) && \str_ends_with($roleId, self::ROLE_ID_SUFFIX)) {
            return new self($roleId);
        }

        return null;
    }
    public function toContentDimensionValue(ContentDimensionPresetSourceInterface $contentDimensionPresetSource): string
    {
        $languagePresets = $contentDimensionPresetSource->getAllPresets()['language']['presets'];

        $roleDimension = rtrim(ltrim($this->value, self::ROLE_ID_PREFIX), self::ROLE_ID_SUFFIX);
        $roleDimension = $roleDimension === 'German' ? 'Deutsch' : $roleDimension;

        foreach ($languagePresets as $languagePreset) {
            if ($languagePreset['label'] === $roleDimension) {
                return reset($languagePreset['values']);
            }
        }

        throw LanguageBasedRoleIsInvalid::becauseItMustContainOfTheDefinedPresetLabels($roleDimension);
    }
}
