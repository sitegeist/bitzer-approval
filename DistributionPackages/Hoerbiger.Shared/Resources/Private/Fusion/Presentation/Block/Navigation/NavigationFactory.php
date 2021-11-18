<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Navigation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItem;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Exception;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class NavigationFactory extends AbstractComponentPresentationObjectFactory
{
    public function forMainNavigation(TraversableNodeInterface $siteNode, TraversableNodeInterface $currentDocumentNode): NavigationInterface
    {
        $navigationItems = $this->getChildNavigationNodes($siteNode, $currentDocumentNode,1);
        return new Navigation($navigationItems);
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
    ) {
        $menuItems = [];

        if ($currentLevel <= $maxDepth) {
            $childNodes = $this->findChildNodesByNodeTypeFilterString($node, 'Neos.Neos:Document');

            $currentLevel++;

            foreach ($childNodes as $childNode) {

                /** @var Node  $childNode*/
                if (!$childNode->isHiddenInIndex()) {
                    try {
                        $menuItems[] = new NavigationItem(
                            (new LinkFactory())->forLinkToDocument($childNode),
                            $this->getChildNavigationNodes(
                                $childNode,
                                $currentDocumentNode,
                                $currentLevel
                            ),
                            $this->isParentNodeOfCurrentDocument($childNode, $currentDocumentNode)
                        );

                    } catch (Exception $e) {
                    }
                }
            }
        }

        return new NavigationItems($menuItems);
    }

    protected function isParentNodeOfCurrentDocument(TraversableNodeInterface $childDocument, TraversableNodeInterface $currentDocument): bool
    {
        $childNodePath = rtrim((string)$childDocument->findNodePath(), '/') . '/';
        return str_starts_with($currentDocument->findNodePath() . '/',$childNodePath );
    }
}
