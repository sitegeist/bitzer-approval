<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Command;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Domain\Repository\SiteRepository;

/**
 * The command controller for XML sitemap handling
 * @Flow\Scope("singleton")
 */
class CleanupCommandController extends CommandController
{
    #[Flow\Inject]
    protected NodeDataRepository $nodeDataRepository;

    #[Flow\Inject]
    protected SiteRepository $siteRepository;

    public function dimensionLessNodesCommand(): void
    {
        $query = $this->nodeDataRepository->createQuery();

        $dimensionlessNodes = $query->matching($query->equals('dimensionValues', '{}'))->execute();
        $dimensionlessNodeAssociations = [];

        /** @var NodeData $dimensionlessNode */
        foreach ($dimensionlessNodes as $dimensionlessNode) {
            $path = $dimensionlessNode->getPath();
            $dimensionlessNodeAssociations[$path] = $dimensionlessNode;
        }

        unset($dimensionlessNodeAssociations['/']);
        unset($dimensionlessNodeAssociations['/sites']);

        uksort(
            $dimensionlessNodeAssociations,
            fn (string $pathA, string $pathB)
            =>
                substr_count($pathB, '/')
                <=>
                substr_count($pathA, '/')
        );

        foreach ($dimensionlessNodeAssociations as $path => $dimensionlessNode) {
            $query = $this->nodeDataRepository->createQuery();
            $nodeVariants = $query->matching($query->logicalAnd([
                $query->equals('identifier', $dimensionlessNode->getIdentifier()),
                $query->logicalNot($query->equals('dimensionValues', '{}'))
            ]))->execute();

            if ($nodeVariants->count()) {
                $dimensionlessNode->remove();
                $this->output->outputLine('removed: ' . $dimensionlessNode->getPath());
            } else {
                $query = $this->nodeDataRepository->createQuery();
                $parentNodes = $query->matching($query->logicalAnd([
                    $query->equals('path', $dimensionlessNode->getParentPath()),
                    $query->logicalNot($query->equals('dimensionValues', '{}'))
                ]))->execute();
                if ($parentNodes->count() !== 1) {
                    // the node where manually checked and where inside Test Pages
                    $dimensionlessNode->remove();
                    $this->output->outputLine('removed: ' . $dimensionlessNode->getPath());
                } else {
                    /** @var NodeData $parentNode */
                    $parentNode = $parentNodes->getFirst();
                    $dimensionlessNode->setDimensions($parentNode->getDimensions());
                    $this->output->outputLine('dimension set: ' . $dimensionlessNode->getPath());
                }
            }
        }
    }
}
