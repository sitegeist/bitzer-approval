<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItem;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Exception;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
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
        TraversableNodeInterface $site ,
        ContentContext $subgraph
    ): SiteHeaderInterface {
        return new SiteHeader(
            $this->getChildNavigationNodes($site, $document, 1),
            Value::fromString('Search'),
            $this->getLanguageNavigationItems($document, $subgraph),
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
        int $maxDepth = 2
    ): ?NavigationItems {
        if ($currentLevel <= $maxDepth) {
            $menuItems = [];
            $childNodes = $this->findChildNodesByNodeTypeFilterString($node, 'Neos.Neos:Document');

            foreach ($childNodes as $childNode) {
                /** @var Node $childNode*/
                if (!$childNode->isHiddenInIndex()) {
                    try {
                        $menuItems[] = new NavigationItem(
                            $this->linkFactory->forLinkToDocument($childNode)->asPlainLabel(),
                            $this->getChildNavigationNodes(
                                $childNode,
                                $currentDocumentNode,
                                $currentLevel + 1
                            ),
                            $this->isParentNodeOfCurrentDocument($childNode, $currentDocumentNode)
                        );

                    } catch (Exception $e) {}
                }
            }

            if (count($menuItems)) {
                return new NavigationItems($menuItems);
            }
        }

        return null;
    }

    protected function isParentNodeOfCurrentDocument(TraversableNodeInterface $childDocument, TraversableNodeInterface $currentDocument): bool
    {
        $childNodePath = rtrim((string)$childDocument->findNodePath(), '/') . '/';
        return str_starts_with($currentDocument->findNodePath() . '/',$childNodePath );
    }

    protected function getLanguageNavigationItems(TraversableNodeInterface $documentNode, ContentContext $subgraph ): NavigationItems
    {
        /* Languages */
        $currentLanguageIdentifier = strtoupper(reset($subgraph->getDimensions()['language']));
        $languages = [];

        foreach ($this->contentDimensionPresetSource->getAllPresets()['language']['presets'] as $localeIdentifier => $preset) {

            $contextProperties = $subgraph->getProperties();

            $contextProperties['dimensions'] = [
                'language' => $preset['values']
            ];

            $contextProperties['targetDimensions'] = [
                'language' => reset($preset['values'])
            ];

            $contentContext = $this->contentContextFactory->create($contextProperties);

            /** @var TraversableNodeInterface $variant */
            $variant = $contentContext->getNodeByIdentifier((string)$documentNode->getNodeAggregateIdentifier());

            $identifier = strtoupper($localeIdentifier);

            $language = [$this->uriService->getNodeUri($variant), $identifier];

            $currentLanguageIdentifier === $identifier ?
                array_unshift($languages, $language) :
                $languages[] = $language;

        }
        $current = $languages[0];

        array_shift($languages);

        $children = [];

        foreach ($languages as $language) {
            $children[] = new NavigationItem(
                $this->linkFactory->forLanguageMenuItem(
                    $language[0],
                    $language[1]
                ),
                null,
                false
            );
        }

        return new NavigationItems([
            new NavigationItem(
                $this->linkFactory->forLanguageMenuItem(
                    $current[0],
                    $current[1]
                ),
                new NavigationItems($children),
                true
            )
        ]);
    }
}
