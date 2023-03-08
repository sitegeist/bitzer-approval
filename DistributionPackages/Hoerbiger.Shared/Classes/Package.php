<?php
namespace Hoerbiger\Shared;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Application\PublishWatch;
use Hoerbiger\Shared\Infrastructure\AssetIndexingManager;
use Hoerbiger\Shared\Infrastructure\AssetMetadataManager;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Core\Booting\Sequence;
use Neos\Flow\Core\Booting\Step;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap): void
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $package = $this;
        $dispatcher->connect(
            Sequence::class,
            'afterInvokeStep',
            function (Step $step) use ($package, $bootstrap) {
                if ($step->getIdentifier() === 'neos.flow:objectmanagement:runtime') {
                    $package->registerAssetIndexingSlots($bootstrap);
                }
            }
        );

        $dispatcher->connect(
            Workspace::class,
            'beforeNodePublishing',
            PublishWatch::class,
            'watchForRedundantContentElementIdentifier'
        );
    }

    public function registerAssetIndexingSlots(Bootstrap $bootstrap): void
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = $bootstrap->getObjectManager()->get(ConfigurationManager::class);
        $settings = $configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            'Neos.ContentRepository.Search'
        );
        if ($settings['realtimeIndexing']['enabled'] ?? false) {
            // handle changes to assets

            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetCreated',
                AssetMetadataManager::class,
                'whenAssetWasCreated'
            );
            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetUpdated',
                AssetMetadataManager::class,
                'whenAssetWasUpdated'
            );
            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetRemoved',
                AssetMetadataManager::class,
                'whenAssetWasRemoved'
            );

            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetCreated',
                AssetIndexingManager::class,
                'whenAssetWasCreated'
            );
            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetUpdated',
                AssetIndexingManager::class,
                'whenAssetWasUpdated'
            );
            $bootstrap->getSignalSlotDispatcher()->connect(
                AssetService::class,
                'assetRemoved',
                AssetIndexingManager::class,
                'whenAssetWasRemoved'
            );
        }
    }
}
