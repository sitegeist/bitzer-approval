<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Service\ThumbnailService;
use Neos\Media\Exception\ThumbnailServiceException;

/**
 * @Flow\Scope("singleton")
 */
final class SourceSetProvider
{
    private ThumbnailService $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

    /**
     * @throws ThumbnailServiceException
     */
    public function getFullSet(): SourceSet
    {
        return $this->mapPresetIdentifiersToSourceSet(array_keys($this->thumbnailService->getPresets()));
    }

    /**
     * @param array<int,PresetIdentifier> $presetIdentifiers
     * @throws ThumbnailServiceException
     */
    public function getSourceSetForPresets(array $presetIdentifiers): SourceSet
    {
        $extendedPresetIdentifiers = [];
        foreach ($presetIdentifiers as $presetIdentifier) {
            $extendedPresetIdentifiers[] = $presetIdentifier->value;
            $extendedPresetIdentifiers[] = $presetIdentifier->value . '2';
        }
        return $this->mapPresetIdentifiersToSourceSet($extendedPresetIdentifiers);
    }

    /**
     * @param array<int,string> $presetIdentifiers
     * @throws ThumbnailServiceException
     */
    private function mapPresetIdentifiersToSourceSet(array $presetIdentifiers): SourceSet
    {
        $thumbnailConfigurations = [];
        foreach ($presetIdentifiers as $presetIdentifier) {
            $thumbnailConfigurations[$presetIdentifier] = $this->thumbnailService->getThumbnailConfigurationForPreset($presetIdentifier);
        }
        return new SourceSet($thumbnailConfigurations);
    }
}
