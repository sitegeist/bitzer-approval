<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\HR\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.HR package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Application\CompactSearchForm\CompactSearchFormFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItem;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderInterface;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Exception;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Neos\ContentRepository\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Neos\Domain\Service\ContentContextFactory;


final class SiteHeaderFactory extends AbstractComponentPresentationObjectFactory
{
    private ContentDimensionPresetSourceInterface $contentDimensionPresetSource;

    private ContentContextFactory $contentContextFactory;

    private LinkFactory $linkFactory;

    public function __construct(
        ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        LinkFactory $linkFactory,
        ContentContextFactory $contentContextFactory
    ) {
        $this->contentDimensionPresetSource = $contentDimensionPresetSource;
        $this->linkFactory = $linkFactory;
        $this->contentContextFactory = $contentContextFactory;
    }

    public function forDocument(
        TraversableNodeInterface $document,
        TraversableNodeInterface $site,
        ContentContext $subgraph,
        ?ActionRequest $actionRequest
    ): SiteHeaderInterface {
        return new SiteHeader(
            $this->getChildNavigationNodes($site, $document, 1),
            (new CompactSearchFormFactory())->forDocument($site),
            $this->getLanguageNavigationItems($document, $subgraph, $actionRequest ),
            new Icon("shared", "chevronDown", IconSize::size_1_5()),
            new Icon("shared", "arrowRight", IconSize::size_1())
        );
    }

    /**
     * @param TraversableNodeInterface $node
     * @param int $currentLevel
     * @param int $maxDepth
     * @return NavigationItems
     * @throws Exception
     * @throws \Neos\Flow\Http\Exception
     * @throws \Neos\Flow\Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     */
    protected function getChildNavigationNodes(
        TraversableNodeInterface $node,
        TraversableNodeInterface $currentDocumentNode,
        int $currentLevel,
        int $maxDepth = 2,
        int $maxItemsPerColumn = 7
    ): ?NavigationItems {
        if ($currentLevel <= $maxDepth) {
            $menuItems = [];
            $childNodes = $this->findChildNodesByNodeTypeFilterString($node, 'Neos.Neos:Document,!Hoerbiger.Shared:Tag.ExcludeFromNavigation');

            foreach ($childNodes as $childNode) {
                /** @var Node $childNode*/
                if (!$childNode->isHiddenInIndex()) {
                    try {
                        $menuItems[] = new NavigationItem(
                            $this->linkFactory->forLinkToDocument($childNode, null)->asPlainLabel(),
                            $this->getChildNavigationNodes(
                                $childNode,
                                $currentDocumentNode,
                                $currentLevel + 1
                            ),
                            $this->isParentNodeOfCurrentDocument($childNode, $currentDocumentNode)
                        );
                    } catch (Exception $e) {
                    }
                }
            }

            if (count($menuItems)) {
                if ($currentLevel <= 1) {
                    return new NavigationItems($menuItems);
                } else {
                    $totalItems = count($menuItems);
                    $countColumns = ceil($totalItems / $maxItemsPerColumn);

                    $newMenuItems = [];

                    for ($i = 0; $i <= $countColumns - 1; $i++) {
                        $newMenuItems[$i] = new NavigationItem(
                            $menuItems[$i]->getLink(),
                            new NavigationItems(array_slice($menuItems, ($i * $maxItemsPerColumn), $maxItemsPerColumn)),
                            true
                        );
                    };

                    return new NavigationItems($newMenuItems);
                }
            }
        }

        return null;
    }

    protected function isParentNodeOfCurrentDocument(TraversableNodeInterface $childDocument, TraversableNodeInterface $currentDocument): bool
    {
        $childNodePath = rtrim((string)$childDocument->findNodePath(), '/') . '/';
        return str_starts_with($currentDocument->findNodePath() . '/', $childNodePath);
    }

    protected function getLanguageNavigationItems(
        TraversableNodeInterface $documentNode,
        ContentContext $subgraph,
        ?ActionRequest $request
    ): NavigationItems {
        /* Languages */
        $currentLanguageIdentifier = strtoupper(reset($subgraph->getDimensions()['language']));
        $languages = [];

        $queryString = $request ? $request->getHttpRequest()->getServerParams()['QUERY_STRING'] : null;

        foreach ($this->contentDimensionPresetSource->getAllPresets()['language']['presets'] as $localeIdentifier => $preset) {
            $contextProperties = $subgraph->getProperties();

            $contextProperties['dimensions'] = [
                'language' => $preset['values']
            ];

            $contextProperties['targetDimensions'] = [
                'language' => reset($preset['values'])
            ];

            /** @var ContentContext $contentContext */
            $contentContext = $this->contentContextFactory->create($contextProperties);

            /** @var TraversableNodeInterface $variant */
            /** @phpstan-ignore-next-line */
            $variant = $contentContext->getNodeByIdentifier((string)$documentNode->getNodeAggregateIdentifier())
                ? $contentContext->getNodeByIdentifier((string)$documentNode->getNodeAggregateIdentifier())
                : $contentContext->getCurrentSiteNode();

            $identifier = strtoupper($localeIdentifier);

            $language = [$this->uriService->getNodeUri($variant), $identifier];
            $languages[] = $language;
        }
        $children = [];

        foreach ($languages as $language) {
            /** @var Uri $documentUri */
            $documentUri = $language[0] ;

            $children[] = new NavigationItem(
                $this->linkFactory->forLanguageMenuItem(
                    $queryString ? $documentUri->withQuery($queryString): $documentUri,
                    $language[1]
                ),
                null,
                $currentLanguageIdentifier === $language[1]
            );
        }

        return new NavigationItems($children);
    }
}
