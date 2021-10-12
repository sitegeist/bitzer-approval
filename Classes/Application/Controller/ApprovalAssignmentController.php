<?php
declare(strict_types=1);

namespace Sitegeist\Bitzer\Approval\Application\Controller;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Fusion\View\FusionView;
use Neos\Neos\Controller\Backend\ModuleController;
use Sitegeist\Bitzer\Approval\Domain\Approval\AgentRepository;
use Sitegeist\Bitzer\Approval\Domain\Approval\ApprovalAssignmentIdentifier;
use Sitegeist\Bitzer\Approval\Domain\Approval\ApprovalAssignmentRepository;
use Sitegeist\Bitzer\Approval\Domain\Approval\WorkspaceRepository;

/**
 * The bitzer controller for approval process actions
 *
 * @Flow\Scope("singleton")
 */
final class ApprovalAssignmentController extends ModuleController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @var FusionView
     */
    protected $view;

    private ApprovalAssignmentRepository $approvalAssignmentRepository;

    private WorkspaceRepository $workspaceRepository;

    private AgentRepository $agentRepository;

    private Translator $translator;

    public function __construct(
        ApprovalAssignmentRepository $approvalAssignmentRepository,
        WorkspaceRepository $workspaceRepository,
        AgentRepository $agentRepository,
        Translator $translator
    ) {
        $this->approvalAssignmentRepository = $approvalAssignmentRepository;
        $this->workspaceRepository = $workspaceRepository;
        $this->agentRepository = $agentRepository;
        $this->translator = $translator;
    }

    public function indexAction(array $module = []): void
    {
        $this->view->assignMultiple([
            'approvalAssignments' => $this->approvalAssignmentRepository->findAll(),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken(),
            'assignmentFormUri' => $this->getActionUri('assignmentForm'),
            'reassignmentFormUri' => $this->getActionUri('reassignmentForm'),
            'removeAssignmentUri' => $this->getActionUri('removeAssignment'),
            'labels' => [
                'assign' => $this->getLabel('index.assign'),
                'assignmentLegend' => $this->getLabel('index.assignmentLegend'),
                'workspace' => $this->getLabel('index.workspace'),
                'agent' => $this->getLabel('index.agent'),
                'actions' => $this->getLabel('index.actions'),
                'reassign' => $this->getLabel('index.reassign'),
                'removeAssignment' => $this->getLabel('index.removeAssignment'),
                'removeAssignmentWarning' => $this->getLabel('index.removeAssignmentWarning'),
                'removeAssignmentExplanation' => $this->getLabel('index.removeAssignmentExplanation'),
                'removeAssignmentCancellation' => $this->getLabel('index.removeAssignmentCancellation'),
                'removeAssignmentConfirmation' => $this->getLabel('index.removeAssignmentConfirmation')
            ]
        ]);
    }

    public function assignmentFormAction(): void
    {
        $this->view->assignMultiple([
            'workspaces' => $this->workspaceRepository->findApprovable(),
            'agents' => $this->agentRepository->findApplicable(),
            'assignUri' => $this->getActionUri('assign'),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken(),
            'labels' => [
                'assign' => $this->getLabel('assignmentForm.assign'),
                'workspace' => $this->getLabel('assignmentForm.workspace'),
                'agent' => $this->getLabel('assignmentForm.agent'),
                'assignmentConfirmation' => $this->getLabel('assignmentForm.assignmentConfirmation')
            ]
        ]);
    }

    public function assignAction(array $assignmentData): void
    {
        $this->approvalAssignmentRepository->assign($assignmentData);
        $this->message('workspaceAssigned', [
            $assignmentData['workspaceName'],
            $assignmentData['responsibleAgentIdentifier']
        ]);
        $this->redirect('index');
    }

    public function reassignmentFormAction(string $identifier): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->view->assignMultiple([
            'assignment' => $this->approvalAssignmentRepository->findByIdentifier($assignmentIdentifier),
            'workspaceName' => $assignmentIdentifier->getWorkspaceName(),
            'agents' => $this->agentRepository->findApplicable(),
            'reassignUri' => $this->getActionUri('reassign'),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken(),
            'labels' => [
                'reassign' => $this->getLabel('reassignmentForm.reassign'),
                'workspace' => $this->getLabel('reassignmentForm.workspace'),
                'agent' => $this->getLabel('reassignmentForm.agent'),
                'reassignmentConfirmation' => $this->getLabel('reassignmentForm.reassignmentConfirmation')
            ]
        ]);
    }

    public function reassignAction(string $identifier, array $assignmentData): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->approvalAssignmentRepository->reassign($assignmentIdentifier, $assignmentData);
        $this->message('workspaceReassigned', [
            $assignmentIdentifier->getWorkspaceName(),
            $assignmentData['responsibleAgentIdentifier']
        ]);
        $this->redirect('index');
    }

    public function removeAssignmentAction(string $identifier): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->approvalAssignmentRepository->removeAssignment($assignmentIdentifier);
        $this->message('workspaceAssignmentRemoved', [
            $assignmentIdentifier->getWorkspaceName(),
            $assignmentIdentifier->getResponsibleAgentIdentifier()
        ]);
        $this->redirect('index');
    }

    private function getActionUri(string $actionName, array $parameters = []): Uri
    {
        return new Uri($this->controllerContext
            ->getUriBuilder()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                $actionName,
                $parameters,
                'ApprovalAssignment',
                'Sitegeist.Bitzer.Approval',
                'Application'
            ));
    }

    private function message(string $event, array $arguments = []): void
    {
        $this->addFlashMessage(
            $this->getLabel('flashMessage.' . $event . '.body', $arguments),
            $this->getLabel('flashMessage.' . $event . '.title')
        );
    }

    private function getLabel(string $id, array $arguments = []): string
    {
        return $this->translator->translateById(
            $id,
            $arguments,
            null,
            null,
            'Module.Bitzer.Approval',
            'Sitegeist.Bitzer.Approval'
        ) ?: $id;
    }

    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);
        /** @var FusionView $view */
        $view->setFusionPathPattern(
            'resource://Sitegeist.Bitzer.Approval/Private/Fusion/Integration/ApprovalAssignment'
        );
        $view->assignMultiple([
            'flashMessages' => $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush()
        ]);
    }
}
