<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\ContentRepository\Domain\Service\Context;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

#[Flow\Scope('singleton')]
final class TranslationContentContextFactory
{
    public function __construct(
        private readonly ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        private readonly ContentContextFactory $contentContextFactory,
    ) {
    }

    public function createForLanguageDimensionAndWorkspaceName(
        string $language,
    ): Context {
        $dimensionValues = $this->contentDimensionPresetSource->getAllPresets()
            ['language']['presets'][$language]['values'];

        return $this->contentContextFactory->create(
            array(
                'workspaceName' => 'live',
                'invisibleContentShown' => true,
                'removedContentShown' => true,
                'inaccessibleContentShown' => true,
                'dimensions' => [
                    'language' => $dimensionValues
                ],
                'targetDimensions' => [
                    'language' => reset($dimensionValues)
                ]
            )
        );
    }
}
