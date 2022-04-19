<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Command;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * The application world command controller
 */
class ApplicationWorldCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var NodeDataRepository
     */
    protected $nodeDataRepository;

    public function setVisibilityCommand(string $nodeName, bool $hidden): void
    {
        $query = $this->nodeDataRepository->createQuery();

        $nodeRecords = $query->matching(
            $query->equals('path', '/sites/hoerbiger-hoerbigercom/' . $nodeName)
        )->execute();

        foreach ($nodeRecords as $nodeRecord) {
            /** @var NodeData $nodeRecord */
            if (is_bool($hidden)) {
                $nodeRecord->setHidden($hidden);
            }
        }
    }
}
