<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MostWanted;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Fusion\Core\Cache\ContentCache;

/**
 * The handler for MostWanted commands
 *
 * @Flow\Scope("singleton")
 */
final class WantedNodeCommandHandler
{
    private ContentContextFactory $contentContextFactory;

    private WantedNodeRepository $wantedNodeRepository;

    private ContentCache $contentCache;

    public function __construct(
        ContentContextFactory $contentContextFactory,
        WantedNodeRepository $wantedNodeRepository,
        ContentCache $contentCache
    ) {
        $this->contentContextFactory = $contentContextFactory;
        $this->wantedNodeRepository = $wantedNodeRepository;
        $this->contentCache = $contentCache;
    }

    /**
     * @throws NodeCannotBeRegisteredAsWanted
     */
    public function handleRegisterWantedNode(RegisterWantedNode $command): void
    {
        $this->requireNode($command->getLanguage(), $command->getNodeAggregateIdentifier());
        $this->wantedNodeRepository->registerWantedNode($command);
        $this->contentCache->flushByTag('Hoerbiger.HoerbigerCom:MostWanted');
    }

    /**
     * @throws NodeCannotBeRegisteredAsWanted
     */
    private function requireNode(string $language, NodeAggregateIdentifier $nodeAggregateIdentifier): void
    {
        $contentContext = $this->contentContextFactory->create($language);

        $node = $contentContext->getNodeByIdentifier((string)$nodeAggregateIdentifier);
        if (!$node instanceof TraversableNodeInterface) {
            throw NodeCannotBeRegisteredAsWanted::becauseItDoesNotCoverLanguage(
                $language,
                $nodeAggregateIdentifier
            );
        }
    }
}
