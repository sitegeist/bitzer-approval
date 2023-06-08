<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Model\User;

#[Flow\Proxy(false)]

final class IsEditor
{
    public static function isSatisfiedByUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        /** @phpstan-ignore-next-line */
        $accounts = $user->getAccounts()->getValues();
        $primaryAccount = reset($accounts) ?: null;

        return $primaryAccount instanceof Account
            && (
                $primaryAccount->hasRole(new Role(EditorialStaff::EDITOR_ROLE_ID))
                || $primaryAccount->hasRole(new Role(EditorialStaff::CHIEF_EDITOR_ROLE_ID))
            );
    }
}
