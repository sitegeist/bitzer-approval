<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Command;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;

#[Flow\Scope('singleton')]
class ResetCommandController extends CommandController
{
    #[Flow\Inject]
    protected NodeDataRepository $nodeDataRepository;

    #[Flow\Inject]
    protected ContentContextFactory $contentContextFactory;


    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    public function lastModificationDateCommand(string $dimension): void
    {
        $query = $this->nodeDataRepository->createQuery();

        $dimensionNodes = $query->matching($query->like('dimensionValues', '%' . $dimension . '%', false))->execute();

        $contentContent = $this->createContentContext($dimension);

        for ($i = 0; $i < 2; $i++) {
            /** @var NodeData $nodeData */
            foreach ($dimensionNodes as $nodeData) {
                $node = $contentContent->getNode($nodeData->getPath());

                if (!$node instanceof Node) {
                    continue;
                }

                if ($node->getNodeType()->isOfType('Neos.Neos:Document')) {
                    $node->setHidden(!$node->isHidden());
                }
            }
            $this->persistenceManager->persistAll();
        }
    }

    private function createContentContext(string $language): ContentContext
    {
        /** @var ContentContext $contentContext */
        $contentContext = $this->contentContextFactory->create([
            'dimensions' => ['language' => [$language]],
            'targetDimensions' => ['language' => $language],
            'invisibleContentShown' => true,
            'removedContentShown' => true,
            'inaccessibleContentShown' => true
        ]);

        return $contentContext;
    }
}
