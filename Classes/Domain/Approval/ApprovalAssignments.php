<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\Flow\Annotations as Flow;

/**
 * The approval assignment domain entity
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<int,ApprovalAssignment>
 */
final class ApprovalAssignments implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,ApprovalAssignment>
     */
    private array $approvalAssignments;

    /**
     * @param array<int,mixed> $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            if (!$item instanceof ApprovalAssignment) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . ApprovalAssignment::class);
            }
        }
        $this->approvalAssignments = $items;
    }

    /**
     * @return \ArrayIterator<int,ApprovalAssignment>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->approvalAssignments);
    }

    public function count(): int
    {
        return count($this->approvalAssignments);
    }
}
