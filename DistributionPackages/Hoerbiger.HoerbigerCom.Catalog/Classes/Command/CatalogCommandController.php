<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Command;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Domain\Catalog;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;

class CatalogCommandController extends CommandController
{
    private Catalog $catalog;

    private ContentContextFactory $contentContextFactory;

    public function __construct(Catalog $catalog, ContentContextFactory $contentContextFactory)
    {
        $this->catalog = $catalog;
        $this->contentContextFactory = $contentContextFactory;
        parent::__construct();
    }

    /**
     * List the unused product groups, and optionally remove them.
     *
     * @param string $language Language of the dimension which to analyze
     * @param ?bool $removeUnused Remove all unused groups directly - otherwise it will be asked
     */
    public function unusedProductGroupsCommand(string $language, ?bool $removeUnused = null): void
    {
        $subgraph = $this->createContentContextByLanguage($language);
        $unusedGroups = $this->catalog->findUnusedProductGroups($subgraph);

        if (count($unusedGroups) === 0) {
            $this->outputLine("Found no unused product groups.");
            return;
        }

        $this->outputLine("Found %d unused product groups.", [count($unusedGroups)]);

        $this->output->outputTable(
            array_map(
                fn (TraversableNodeInterface $node) => [(string)$node->getNodeAggregateIdentifier(), $node->getLabel()],
                $unusedGroups->toArray()
            ),
            ["Node identifier", "Label"]
        );

        if ($removeUnused === null) {
            $removeUnused = $this->output->askConfirmation(
                "Should the unused product groups be removed? (y/n) ",
                false
            );
        }

        if (!$removeUnused) {
            return;
        }

        $this->outputLine("Removing unused groups ...");

        $this->removeUnusedProductGroups($unusedGroups, $message);

        $this->output($message);
    }

    /**
     * @param TraversableNodes<int, TraversableNodeInterface> $unusedGroups
     */
    private function removeUnusedProductGroups(TraversableNodes $unusedGroups, ?string &$message): void
    {
        $message = '';
        $removed = 0;
        /** @var Node $unusedGroup */
        foreach ($unusedGroups as $unusedGroup) {
            if ($unusedGroup->isTethered()) {
                $message .= sprintf("\nCannot remove %s, because node is tethered.\n", $unusedGroup);
                continue;
            }
            try {
                $unusedGroup->remove();
            } catch (\Exception $exception) {
                $message .= sprintf("\nCould not remove node %s.\n\n%s\n\n\n", $unusedGroup, $exception);
                continue;
            }
            ++$removed;
        }
        $message .= sprintf("Removed %d nodes.\n", $removed);
    }

    private function createContentContextByLanguage(string $language): ContentContext
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
