<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

#[Flow\Proxy(false)]
final class LanguageBasedRoleId
{
    private const ROLE_ID_PREFIX = 'Hoerbiger.Shared:';
    private const ROLE_ID_SUFFIX = 'Editor';

    private const ABSTRACT_TRANSLATOR_ROLE = 'Hoerbiger.Shared:TranslationEditor';
    public function __construct(
        private readonly string $value
    ) {
    }

    public static function tryFromUser(?User $user): ?self
    {
        $roleId = null;
        foreach ($user?->getAccounts() ?: [] as $account) {
            foreach ($account->getRoles() as $role) {
                $roleId = self::tryFromRole($role);
                if (!$roleId) {
                    return null;
                }
            }
        }
        return $roleId;
    }
    public static function tryFromRole(Role $role): ?self
    {
        $abstractTranslatorRole = new Role(self::ABSTRACT_TRANSLATOR_ROLE);

        if ($role->hasParentRole($abstractTranslatorRole)
        ) {
            return new self($role->getIdentifier());
        }

        return null;
    }
    public function toContentDimensionValue(ContentDimensionPresetSourceInterface $contentDimensionPresetSource): string
    {
        $languagePresets = $contentDimensionPresetSource->getAllPresets()['language']['presets'];

        $roleDimension = \mb_substr($this->value, \mb_strlen(self::ROLE_ID_PREFIX));
        $roleDimension = \mb_substr($roleDimension, 0, -\mb_strlen(self::ROLE_ID_SUFFIX));
        $roleDimension = $roleDimension === 'German' ? 'Deutsch' : $roleDimension;

        foreach ($languagePresets as $languagePreset) {
            if ($languagePreset['label'] === $roleDimension) {
                return reset($languagePreset['values']);
            }
        }

        throw LanguageBasedRoleIsInvalid::becauseItMustContainOfTheDefinedPresetLabels($roleDimension);
    }
}
