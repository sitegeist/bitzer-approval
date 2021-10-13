<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Domain\Approval;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Bitzer\Application\Object\LabeledObjectAddress;
use Sitegeist\Bitzer\Application\Object\LabeledObjectAddresses;
use Sitegeist\Bitzer\Domain\Task\NodeAddress;
use Sitegeist\Bitzer\Infrastructure\ContentContextFactory;

/**
 * The agent domain repository
 * @Flow\Scope("singleton")
 */
final class ObjectRepository
{
    private WorkspaceRepository $workspaceRepository;

    private ContentContextFactory $contentContextFactory;

    private NodeDataRepository $nodeDataRepository;

    public function __construct(
        WorkspaceRepository $workspaceRepository,
        ContentContextFactory $contentContextFactory,
        NodeDataRepository $nodeDataRepository
    ) {
        $this->workspaceRepository = $workspaceRepository;
        $this->contentContextFactory = $contentContextFactory;
        $this->nodeDataRepository = $nodeDataRepository;
    }

    public function findToBeApproved(): LabeledObjectAddresses
    {
        $labeledNodeAddresses = [];
        foreach ($this->workspaceRepository->findApprovable() as $workspace) {
            $query = $this->nodeDataRepository->createQuery();
            $nodeRecords = $query->matching($query->logicalAnd([
                $query->equals('workspace', $workspace),
                $query->logicalNot($query->equals('nodeType', 'unstructured'))
            ]))->execute();

            foreach ($nodeRecords as $nodeRecord) {
                /** @var NodeData $nodeRecord */
                $nodeAddress = NodeAddress::fromNodeData($nodeRecord);
                $contentContext = $this->contentContextFactory->createContentContext($nodeAddress);
                $node = $contentContext->getNodeByIdentifier($nodeRecord->getIdentifier());
                $labeledNodeAddresses[] = new LabeledObjectAddress(
                    NodeAddress::fromNodeData($nodeRecord),
                    $node->getLabel()
                );
            }
        }

        return new LabeledObjectAddresses($labeledNodeAddresses);
    }
}
