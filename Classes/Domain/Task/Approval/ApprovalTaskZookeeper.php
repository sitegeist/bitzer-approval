<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Task\Approval;

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Bitzer\Application\Bitzer;
use Sitegeist\Bitzer\Approval\Domain\Approval\ApprovalAssignmentRepository;
use Sitegeist\Bitzer\Approval\Domain\Approval\WorkspaceRepository;
use Sitegeist\Bitzer\Domain\Task\Command\CompleteTask;
use Sitegeist\Bitzer\Domain\Task\Command\ScheduleTask;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Domain\Task\Schedule;
use Sitegeist\Bitzer\Domain\Task\TaskClassName;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;
use Sitegeist\Bitzer\Domain\Agent\Agent;

/**
 * The approval task handler event listener
 * @Flow\Scope("singleton")
 */
final class ApprovalTaskZookeeper
{
    private bool $isTaskAutoGenerationEnabled;

    private \DateInterval $approvalInterval;

    private ApprovalAssignmentRepository $approvalAssignmentRepository;

    private WorkspaceRepository $workspaceRepository;

    private Schedule $schedule;

    private Bitzer $bitzer;

    public function __construct(
        bool $isTaskAutoGenerationEnabled,
        string $approvalInterval,
        ApprovalAssignmentRepository $approvalAssignmentRepository,
        WorkspaceRepository $workspaceRepository,
        Schedule $schedule,
        Bitzer $bitzer
    ) {
        $this->isTaskAutoGenerationEnabled = $isTaskAutoGenerationEnabled;
        $this->approvalInterval = new \DateInterval($approvalInterval);
        $this->approvalAssignmentRepository = $approvalAssignmentRepository;
        $this->workspaceRepository = $workspaceRepository;
        $this->schedule = $schedule;
        $this->bitzer = $bitzer;
    }

    public function whenNodeAggregateWasPublished(TraversableNodeInterface $node, Workspace $workspace): void
    {
        if ($workspace->getName() === 'live') {
            $object = NodeAddress::fromNode($node);
            $this->markTasksAsComplete($object);
        } elseif ($this->isTaskAutoGenerationEnabled && in_array($workspace, $this->workspaceRepository->findApprovable())) {
            $agents = $this->approvalAssignmentRepository->findResponsibleAgentsForWorkspace($workspace->getName());
            $object = NodeAddress::fromNode($node);
            $object = $object->withWorkspaceName($workspace->getName());
            $this->scheduleApprovalTask($object, $agents);
        }
    }

    /**
     * @param array<int,Agent> $agents
     */
    private function scheduleApprovalTask(NodeAddress $object, array $agents): void
    {
        $activeAgents = [];
        $taskClassName = TaskClassName::createFromString(ApprovalTask::class);
        $tasks = $this->schedule->findActiveOrPotentialTasksForObject($object, $taskClassName);
        foreach ($tasks as $task) {
            $activeAgents[$task->getAgent()->getIdentifier()->toString()] = true;
        }

        foreach ($agents as $agent) {
            if (!isset($activeAgents[$agent->getIdentifier()->toString()])) {
                $this->bitzer->handleScheduleTask(new ScheduleTask(
                    TaskIdentifier::create(),
                    $taskClassName,
                    (new \DateTimeImmutable())->add($this->approvalInterval),
                    $agent,
                    $object,
                    null,
                    ['description' => 'auto generated approval task']
                ));
            }
        }
    }

    private function markTasksAsComplete(NodeAddress $object): void
    {
        $taskClassName = TaskClassName::createFromString(ApprovalTask::class);
        $tasks = $this->schedule->findActiveOrPotentialTasksForObject($object, $taskClassName);
        foreach ($tasks as $task) {
            /** @var ApprovalTask $task */
            $this->bitzer->handleCompleteTask(new CompleteTask(
                $task->getIdentifier()
            ));
        }
    }
}
