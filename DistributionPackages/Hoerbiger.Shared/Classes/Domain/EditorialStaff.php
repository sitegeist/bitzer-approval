<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use Neos\Neos\Utility\User as UserUtility;

/**
 * @Flow\Scope("singleton")
 */

final class EditorialStaff
{
    public const EDITOR_ROLE_ID = 'Neos.Neos:RestrictedEditor';
    public const CHIEF_EDITOR_ROLE_ID = 'Hoerbiger.Shared:ChiefEditor';

    private AccountRepository $accountRepository;
    private UserRepository $userRepository;
    private WorkspaceRepository $workspaceRepository;

    public function __construct(
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        WorkspaceRepository $workspaceRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->workspaceRepository = $workspaceRepository;
    }

    /**
     * @return array<int,User>
     */
    public function findEditors(): array
    {
        $editors = [];
        foreach ($this->userRepository->findAll() as $user) {
            /** @var User $user */
            if (IsEditor::isSatisfiedByUser($user)) {
                $editors[] = $user;
            }
        }

        return $editors;
    }

    public function findEditor(string $accountId): ?User
    {
        $query = $this->userRepository->createQuery();

        /** @var ?User $editor */

        $editor = $query->matching(
        /** @phpstan-ignore-next-line */
            $query->equals('accounts.accountIdentifier', $accountId)
        )->execute()
            ->getFirst();

        return IsEditor::isSatisfiedByUser($editor)
            ? $editor
            : null;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function promoteEditor(string $accountId): void
    {
        $editor = $this->findEditor($accountId);
        if (!$editor) {
            throw new \InvalidArgumentException('Can\'t find editor with account ' . $accountId);
        }
        /** @phpstan-ignore-next-line */
        $accounts = $editor->getAccounts()->getValues();
        $primaryAccount = reset($accounts);
        /** @var Account $primaryAccount */

        if (!$primaryAccount->hasRole(new Role(self::EDITOR_ROLE_ID))) {
            throw new \InvalidArgumentException(
                'Can\'t promote editor ' . $accountId . ', role ' . self::EDITOR_ROLE_ID . ' is missing'
            );
        }

        $primaryAccount->addRole(new Role(self::CHIEF_EDITOR_ROLE_ID));
        $primaryAccount->removeRole(new Role(self::EDITOR_ROLE_ID));
        $this->accountRepository->update($primaryAccount);

        $workspace = $this->workspaceRepository->findByIdentifier(
            UserUtility::getPersonalWorkspaceNameForUsername($primaryAccount->getAccountIdentifier())
        );
        $workspace->setBaseWorkspace($this->workspaceRepository->findByIdentifier('live'));
        $this->workspaceRepository->update($workspace);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function demoteEditor(string $accountId): void
    {
        $editor = $this->findEditor($accountId);
        if (!$editor) {
            throw new \InvalidArgumentException('Can\'t find editor with account ' . $accountId);
        }
        /** @phpstan-ignore-next-line */
        $accounts = $editor->getAccounts()->getValues();
        $primaryAccount = reset($accounts);
        /** @var Account $primaryAccount */

        if (!$primaryAccount->hasRole(new Role(self::CHIEF_EDITOR_ROLE_ID))) {
            throw new \InvalidArgumentException(
                'Can\'t demote editor ' . $accountId . ', role ' . self::CHIEF_EDITOR_ROLE_ID . ' is missing'
            );
        }

        $primaryAccount->addRole(new Role(self::EDITOR_ROLE_ID));
        $primaryAccount->removeRole(new Role(self::CHIEF_EDITOR_ROLE_ID));
        $this->accountRepository->update($primaryAccount);

        $workspace = $this->workspaceRepository->findByIdentifier(
            UserUtility::getPersonalWorkspaceNameForUsername($primaryAccount->getAccountIdentifier())
        );
        $workspace->setBaseWorkspace($this->workspaceRepository->findByIdentifier('review'));
        $this->workspaceRepository->update($workspace);
    }
}
