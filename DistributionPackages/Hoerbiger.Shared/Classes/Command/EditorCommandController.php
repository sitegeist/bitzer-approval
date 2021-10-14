<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use Neos\Neos\Utility\User as UserUtility;

/**
 * A command controller to manage editors
 */
final class EditorCommandController extends CommandController
{
    private UserRepository $userRepository;

    private AccountRepository $accountRepository;

    private WorkspaceRepository $workspaceRepository;

    public function __construct(
        UserRepository $userRepository,
        AccountRepository $accountRepository,
        WorkspaceRepository $workspaceRepository
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
        $this->workspaceRepository = $workspaceRepository;
    }

    public function promoteCommand(string $accountIdentifier): void
    {
        $editor = $this->findEditor($accountIdentifier);
        if (!$editor) {
            $this->outputLine('No editor could be found for account identifier ' . $accountIdentifier);
            return;
        }

        $editorRole = new Role('Neos.Neos:RestrictedEditor');
        $chiefEditorRole = new Role('Hoerbiger.Shared:ChiefEditor');

        /** @var DoctrineCollection<int,Account> $accounts */
        $accounts = $editor->getAccounts();
        /** @var Account $account */
        $account = $accounts->first();
        if ($account->hasRole($editorRole)) {
            $account->removeRole($editorRole);
            $account->addRole($chiefEditorRole);
            $this->accountRepository->update($account);

            $workspace = $this->workspaceRepository->findByIdentifier(
                UserUtility::getPersonalWorkspaceNameForUsername($account->getAccountIdentifier())
            );
            $workspace->setBaseWorkspace($this->workspaceRepository->findByIdentifier('live'));
            $this->workspaceRepository->update($workspace);
        } else {
            $this->outputLine(
                'User for account identifier ' . $accountIdentifier
                    . ' is no RestrictedEditor and thus cannot be promoted.'
            );
            return;
        }
    }

    public function demoteCommand(string $accountIdentifier): void
    {
        $editor = $this->findEditor($accountIdentifier);
        if (!$editor) {
            $this->outputLine('No editor could be found for account identifier ' . $accountIdentifier);
            return;
        }

        $editorRole = new Role('Neos.Neos:RestrictedEditor');
        $chiefEditorRole = new Role('Hoerbiger.Shared:ChiefEditor');

        /** @var DoctrineCollection<int,Account> $accounts */
        $accounts = $editor->getAccounts();
        /** @var Account $account */
        $account = $accounts->first();
        if ($account->hasRole($chiefEditorRole)) {
            $account->removeRole($chiefEditorRole);
            $account->addRole($editorRole);
            $this->accountRepository->update($account);

            $workspace = $this->workspaceRepository->findByIdentifier(
                UserUtility::getPersonalWorkspaceNameForUsername($account->getAccountIdentifier())
            );
            $workspace->setBaseWorkspace($this->workspaceRepository->findByIdentifier('review'));
            $this->workspaceRepository->update($workspace);
        } else {
            $this->outputLine(
                'User for account identifier ' . $accountIdentifier . ' is no Editor and thus cannot be demoted.'
            );
            return;
        }
    }

    private function findEditor(string $accountIdentifier): ?User
    {
        $query = $this->userRepository->createQuery();

        /** @var ?User $editor */

        $editor = $query->matching(
            /** @phpstan-ignore-next-line */
            $query->equals('accounts.accountIdentifier', $accountIdentifier)
        )->execute()
            ->getFirst();

        return $editor;
    }
}
