<?php
namespace Hoerbiger\Shared\Application;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\ServerRequest;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

class DimensionResolutionHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionPresetSource;

    /**
     * @return array<string,mixed>
     */
    public function resolveContextDimensions(): array
    {
        $pathSegments = explode('/', ServerRequest::getUriFromGlobals()->getPath());
        $activePreset = $this->contentDimensionPresetSource->findPresetByUriSegment('language', $pathSegments[1])
            ?: $this->contentDimensionPresetSource->getDefaultPreset('language');
        $values = $activePreset['values'];

        return [
            'dimensions' => [
                'language' => $values
            ],
            'targetDimensions' => [
                'language' => reset($values)
            ]
        ];
    }

    /**
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
