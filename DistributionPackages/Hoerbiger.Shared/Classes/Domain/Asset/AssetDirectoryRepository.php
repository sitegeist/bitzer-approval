<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Model\Site;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentDimensionPresetSourceInterface;

/**
 * The asset directory repository domain repository
 * @Flow\Scope("singleton")
 */
final class AssetDirectoryRepository
{
    protected ContextFactoryInterface $contentContextFactory;

    protected SiteRepository $siteRepository;

    private ContentDimensionPresetSourceInterface $contentDimensionPresetSource;

    public function __construct(
        ContextFactoryInterface $contentContextFactory,
        SiteRepository $siteRepository,
        ContentDimensionPresetSourceInterface $contentDimensionPresetSource
    ) {
        $this->contentContextFactory = $contentContextFactory;
        $this->siteRepository = $siteRepository;
        $this->contentDimensionPresetSource = $contentDimensionPresetSource;
    }

    public function findAssetDirectory(string $language): ?AssetDirectory
    {
        foreach ($this->siteRepository->findAll() as $site) {
            /** @var Site $site */
            $languageValues = $this->contentDimensionPresetSource->getAllPresets()
                ['language']['presets'][$language]['values'];
            $contentContext = $this->contentContextFactory->create([
                'currentSite' => $site,
                'currentDomain' => $site->getPrimaryDomain(),
                'dimensions' => ['language' => $languageValues],
                'targetDimensions' => ['language' => reset($languageValues)]
            ]);
            /** @var ContentContext $contentContext */
            try {
                /** @var TraversableNodeInterface $siteNode */
                $siteNode = $contentContext->getCurrentSiteNode();
                $assetDirectory = $siteNode->findNamedChildNode(NodeName::fromString('assetdirectory'));

                if ($assetDirectory instanceof AssetDirectory) {
                    return $assetDirectory;
                }
            } catch (NodeException $exception) {
                // then don't
            }
        }

        return null;
    }
}
