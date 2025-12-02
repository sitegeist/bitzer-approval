<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Model\User;

/**
 * @implements \IteratorAggregate<LanguageBasedRoleId>
 */
#[Flow\Proxy(false)]
final readonly class LanguageBasedRoleIds implements \IteratorAggregate
{
    /**
     * @var non-empty-array<int,LanguageBasedRoleId>
     */
    public array $items;

    public function __construct(
        LanguageBasedRoleId ...$items,
    ) {
        if (empty($items)) {
            throw new \DomainException('LanguageBasedRoleIds must not be empty', 1764591136);
        }
        $this->items = array_values($items);
    }

    public static function tryFromUser(?User $user): ?self
    {
        $roleIds = null;
        foreach ($user?->getAccounts() ?: [] as $account) {
            foreach ($account->getRoles() as $role) {
                $roleId = LanguageBasedRoleId::tryFromRole($role);
                if (!$roleId) {
                    // this is an editor
                    return null;
                }
                $roleIds[] = $roleId;
            }
        }
        return $roleIds ? new self(...$roleIds) : null;
    }

    public function getFirst(): LanguageBasedRoleId
    {
        return $this->items[array_key_first($this->items)];
    }

    /**
     * @return \Traversable<int,LanguageBasedRoleId>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
