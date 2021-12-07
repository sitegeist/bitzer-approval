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
     * @return array<int,string>|string[]
     */
    public function resolveLanguage(): array
    {
        $pathSegments = explode('/', ServerRequest::getUriFromGlobals()->getPath());

        $contentDimensionPreset = $this->contentDimensionPresetSource->findPresetByUriSegment(
            'language',
            $pathSegments[1]
            ?? 'en'
        );

        if (!$contentDimensionPreset) {
            return ['en'];
        }
        return $contentDimensionPreset['values'];
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
