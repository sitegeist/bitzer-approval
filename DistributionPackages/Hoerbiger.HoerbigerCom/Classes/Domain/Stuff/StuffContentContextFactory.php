<?php

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Neos\Domain\Model\Site;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

class StuffContentContextFactory
{
    private ContentContextFactory $contentContextFactory;

    private ContentDimensionPresetSourceInterface $contentDimensionPresetSource;

    private SiteRepository $siteRepository;

    public function __construct(
        ContentContextFactory $contentContextFactory,
        ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        SiteRepository $siteRepository
    ) {
        $this->contentContextFactory = $contentContextFactory;
        $this->contentDimensionPresetSource = $contentDimensionPresetSource;
        $this->siteRepository = $siteRepository;
    }

    public function createContentContext(string $language): ContentContext
    {
        $languageValues = $this->contentDimensionPresetSource->getAllPresets()
            ['language']['presets'][$language]['values'];
        /** @var ContentContext $contentContext */
        $contentContext = $this->contentContextFactory->create([
            'dimensions' => [
                'language' => $languageValues
            ],
            'targetDimensions' => [
                'language' => reset($languageValues)
            ],
            'currentSite' => $this->siteRepository->findOneByNodeName('hoerbiger-hoerbigercom')
        ]);

        return $contentContext;
    }
}
