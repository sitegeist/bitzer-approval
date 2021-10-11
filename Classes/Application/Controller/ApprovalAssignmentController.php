<?php
declare(strict_types=1);

namespace Sitegeist\Bitzer\Approval\Application\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Controller\Backend\ModuleController;
use Sitegeist\Bitzer\Approval\Domain\Approval\ApprovalAssignmentRepository;
use Sitegeist\Bitzer\Infrastructure\FusionView;

/**
 * The bitzer controller for approval process actions
 *
 * @Flow\Scope("singleton")
 */
class ApprovalAssignmentController extends ModuleController
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

    public function __construct(ApprovalAssignmentRepository $approvalAssignmentRepository)
    {
        $this->approvalAssignmentRepository = $approvalAssignmentRepository;
    }

    public function indexAction(array $module = []): void
    {
        $this->view->setFusionPath('index');
        $this->view->assignMultiple([
            'approvalAssignments' => $this->approvalAssignmentRepository->findAll()
        ]);
    }
}
