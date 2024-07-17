<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom;

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview\AutoTranslationReviewTaskZookeeper;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap): void
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(
            Workspace::class,
            'afterNodePublishing',
            AutoTranslationReviewTaskZookeeper::class,
            'whenNodeWasPublished',
            false
        );
    }
}
