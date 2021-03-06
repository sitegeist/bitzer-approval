<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval;

use Neos\ContentRepository\Domain\Service\PublishingService;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package as BasePackage;
use Sitegeist\Bitzer\Approval\Domain\Task\Approval\ApprovalTaskZookeeper;

/**
 * The Sitegeist.Bitzer.Approval package
 */
class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            PublishingService::class,
            'nodePublished',
            ApprovalTaskZookeeper::class,
            'whenNodeAggregateWasPublished'
        );

        $dispatcher->connect(
            PublishingService::class,
            'nodeDiscarded',
            ApprovalTaskZookeeper::class,
            'whenNodeAggregateWasDiscarded'
        );
    }
}
