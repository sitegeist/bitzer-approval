<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Application\Controller;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Domain\EditorialStaff;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Flow\Security\Policy\Role;
use Neos\Flow\Validation\Error;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Sitegeist\Bitzer\Infrastructure\FusionView;

/**
 * @Flow\Scope("singleton")
 */
class EditorManagementController extends AbstractModuleController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @var FusionView
     */
    protected $view;

    /**
     * @Flow\Inject
     * @var EditorialStaff
     */
    protected $editorialStaff;

    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

    public function indexAction()
    {
        $editorRole = new Role('Neos.Neos:RestrictedEditor');
        $chiefEditorRole = new Role('Hoerbiger.Shared:ChiefEditor');
        $editors = [];
        $chiefEditors = [];
        foreach ($this->editorialStaff->findEditors() as $user) {
            $accounts = $user->getAccounts();
            $primaryAccount = reset($accounts);
            /** @var Account $primaryAccount */
            if ($primaryAccount->hasRole($chiefEditorRole)) {
                $chiefEditors[] = [
                    'username' => $primaryAccount->getAccountIdentifier(),
                    'name' => $user->getName()->getFullName()
                ];
            } elseif ($primaryAccount->hasRole($editorRole)) {
                $editors[] = [
                    'username' => $primaryAccount->getAccountIdentifier(),
                    'name' => $user->getName()->getFullName()
                ];
            }
        }

        $this->view->assignMultiple([
            'demoteUri' => $this->getActionUri('demote'),
            'promoteUri' => $this->getActionUri('promote'),
            'editors' => $editors,
            'chiefEditors' => $chiefEditors,
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken()
        ]);
    }

    public function promoteAction(string $accountId): void
    {
        try {
            $this->editorialStaff->promoteEditor($accountId);
            $this->addFlashMessage(
                $accountId . ' wurde erfolgreich befördert',
                'Operation successful'
            );
        } catch (\Exception $e) {
            $this->addFlashMessage(
                $e->getMessage(),
                'Operation erfolgreich',
                Error::SEVERITY_WARNING
            );
        }

        $this->redirect('index');
    }

    public function demoteAction(string $accountId): void
    {
        try {
            $this->editorialStaff->demoteEditor($accountId);
            $this->addFlashMessage(
                $accountId . ' wurde erfolgreich zurückgestuft',
                'Operation erfolgreich'
            );
        } catch (\Exception $e) {
            $this->addFlashMessage(
                $e->getMessage(),
                'Operation failed',
                Error::SEVERITY_WARNING
            );
        }

        $this->redirect('index');
    }

    /**
     * @phpstan-param array<mixed,mixed> $parameters
     */
    private function getActionUri(string $actionName, array $parameters = []): Uri
    {
        return new Uri($this->controllerContext
            ->getUriBuilder()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                $actionName,
                $parameters,
                'EditorManagement',
                'Hoerbiger.Shared',
                'Application'
            ));
    }

    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);
        /** @var \Neos\Fusion\View\FusionView $view */
        $view->setFusionPathPattern(
            'resource://Hoerbiger.Shared/Private/Fusion/Integration/EditorManagement'
        );
        $view->assignMultiple([
            'flashMessages' => $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush()
        ]);
    }
}
