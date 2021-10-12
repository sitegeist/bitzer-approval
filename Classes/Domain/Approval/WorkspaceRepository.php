<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository as CoreWorkspaceRepository;
use Neos\Flow\Annotations as Flow;

/**
 * The workspace domain repository
 * @Flow\Scope("singleton")
 */
final class WorkspaceRepository
{
    private CoreWorkspaceRepository $workspaceRepository;

    public function __construct(CoreWorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    /**
     * @return array<int,Workspace>
     */
    public function findApprovable(): array
    {
        $query = $this->workspaceRepository->createQuery();

        return $query->matching(
            $query->logicalAnd([
                $query->equals('baseWorkspace', 'live'),
                $query->equals('owner', null)
            ])
        )->execute()->toArray();
    }
}
