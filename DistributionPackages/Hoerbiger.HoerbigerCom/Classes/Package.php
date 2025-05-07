<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom;

use Hoerbiger\HoerbigerCom\ContentRepository\NodeTranslationService;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview\AutoTranslationReviewTaskZookeeper;
use Neos\ContentRepository\Domain\Service\Context;

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

        $dispatcher->connect(
            Context::class,
            'afterAdoptNode',
            NodeTranslationService::class,
            'afterAdoptNode',
            false
        );
    }
}
