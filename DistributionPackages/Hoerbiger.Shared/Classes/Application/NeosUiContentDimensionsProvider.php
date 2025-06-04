<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Application;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Neos\Service\UserService;
use Hoerbiger\Shared\Infrastructure\LanguageBasedRoleId;

class NeosUiContentDimensionsProvider implements ProtectedContextAwareInterface
{
    public function __construct(
        private readonly ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        private readonly UserService $userService
    ) {
    }

    /**
     * @param array<string,array<int, string>> $dimensions
     * @return array<string,array<int, int|string>>
     */
    public function getAllowedPresetsByName(array $dimensions): array
    {
        $allowedPresets = [];
        $preselectedDimensionPresets = [];
        foreach ($dimensions as $dimensionName => $dimensionValues) {
            $preset = $this->contentDimensionPresetSource->findPresetByDimensionValues(
                $dimensionName,
                $dimensionValues
            );
            if ($preset !== null) {
                $preselectedDimensionPresets[$dimensionName] = $preset['identifier'];
            }
        }
        $roleId = LanguageBasedRoleId::tryFromUser($this->userService->getBackendUser());

        foreach ($preselectedDimensionPresets as $dimensionName => $presetName) {
            $presets = $this->contentDimensionPresetSource->getAllowedDimensionPresetsAccordingToPreselection(
                $dimensionName,
                $preselectedDimensionPresets
            );
            if ($dimensionName === 'language' && $roleId) {
                $allowedPresets[$dimensionName] = [
                    $roleId->toContentDimensionValue($this->contentDimensionPresetSource)
                ];
            } else {
                $allowedPresets[$dimensionName] = array_keys($presets[$dimensionName]['presets']);
            }
        }

        return $allowedPresets;
    }

    /**
     * @return array<string,mixed> Dimensions indexed by name with presets indexed by name
     */
    public function contentDimensionsByName(): array
    {
        $availablePresets = $this->contentDimensionPresetSource->getAllPresets();
        $roleId = LanguageBasedRoleId::tryFromUser($this->userService->getBackendUser());
        if ($roleId) {
            $availablePresets['language']['presets'] = [
                $roleId->toContentDimensionValue($this->contentDimensionPresetSource)
                => $availablePresets['language']['presets'][
                    $roleId->toContentDimensionValue($this->contentDimensionPresetSource)
                ]
            ];
        }
        return $availablePresets;
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
