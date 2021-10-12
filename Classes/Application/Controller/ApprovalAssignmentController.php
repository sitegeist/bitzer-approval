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
            'newUri' => $this->getActionUri('new'),
            'editUri' => $this->getActionUri('edit'),
            'deleteUri' => $this->getActionUri('delete'),
            'labels' => [
                'create' => $this->getLabel('index.create'),
                'creationLegend' => $this->getLabel('index.creationLegend'),
                'workspace' => $this->getLabel('index.workspace'),
                'agent' => $this->getLabel('index.agent'),
                'actions' => $this->getLabel('index.actions'),
                'edit' => $this->getLabel('index.edit'),
                'delete' => $this->getLabel('index.delete'),
                'deleteWarning' => $this->getLabel('index.deleteWarning'),
                'deleteExplanation' => $this->getLabel('index.deleteExplanation'),
                'deleteCancellation' => $this->getLabel('index.deleteCancellation'),
                'deleteConfirmation' => $this->getLabel('index.deleteConfirmation')
            ]
        ]);
    }

    public function newAction(): void
    {
        $this->view->assignMultiple([
            'workspaces' => $this->workspaceRepository->findApprovable(),
            'agents' => $this->agentRepository->findApplicable(),
            'createUri' => $this->getActionUri('create'),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken(),
            'labels' => [
                'create' => $this->getLabel('new.create'),
                'workspace' => $this->getLabel('new.workspace'),
                'agent' => $this->getLabel('new.agent'),
                'createConfirmation' => $this->getLabel('new.createConfirmation')
            ]
        ]);
    }

    public function createAction(array $assignmentData): void
    {
        $this->approvalAssignmentRepository->createAssignment($assignmentData);
        $this->message('workspaceAssigned', [
            $assignmentData['workspaceName'],
            $assignmentData['responsibleAgentIdentifier']
        ]);
        $this->redirect('index');
    }

    public function editAction(string $identifier): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->view->assignMultiple([
            'assignment' => $this->approvalAssignmentRepository->findByIdentifier($assignmentIdentifier),
            'workspaces' => $this->workspaceRepository->findApprovable(),
            'agents' => $this->agentRepository->findApplicable(),
            'updateUri' => $this->getActionUri('update'),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken(),
            'labels' => [
                'update' => $this->getLabel('edit.update'),
                'workspace' => $this->getLabel('edit.workspace'),
                'agent' => $this->getLabel('edit.agent'),
                'updateConfirmation' => $this->getLabel('edit.updateConfirmation')
            ]
        ]);
    }

    public function updateAction(string $identifier, array $assignmentData): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->approvalAssignmentRepository->updateAssignment($assignmentIdentifier, $assignmentData);
        $this->message('workspaceReassigned', [
            $assignmentData['workspaceName'],
            $assignmentData['responsibleAgentIdentifier']
        ]);
        $this->redirect('index');
    }

    public function deleteAction(string $identifier): void
    {
        $assignmentIdentifier = ApprovalAssignmentIdentifier::fromString($identifier);
        $this->approvalAssignmentRepository->deleteAssignment($assignmentIdentifier);
        $this->message('workspaceUnassigned', [
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
