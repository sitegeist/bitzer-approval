<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Figure\Figure;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Content.ImageOverlappedByText':
                return $this->forImageOverLappedByTextNode($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.TileNavigation':
                return $this->forTileNavigation($contentNode);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        /** @var SlotInterface $partialFigureEclipse */
        $partialFigureEclipse = (new PartialFigureEclipseFactory())->forImageOverLappedByTextNode($node, $subgraph, $inBackend);

        return $partialFigureEclipse;
    }

    private function forTileNavigation(TraversableNodeInterface $node): SlotInterface
    {
        return Collection::fromNodes(
            TraversableNodes::fromArray($node->getProperty('documents') ?: []),
            function (TraversableNodeInterface $document) {
                $figure = null;
                $imageSource = null;
                if ($document->getProperty('preview__image')) {
                    $imageSource = new AssetImageSourceHelper($document->getProperty('preview__image'));
                    $imageSource->setAlt($document->getProperty('preview__image__alt') ?: $document->getProperty('image__alt') ?: '');
                    $imageSource->setTitle($document->getProperty('preview__image__title') ?: $document->getProperty('image__title') ?: '');
                } elseif ($document->getProperty('image')) {
                    $imageSource = new AssetImageSourceHelper($document->getProperty('image'));
                    $imageSource->setAlt($document->getProperty('image__alt') ?: '');
                    $imageSource->setTitle($document->getProperty('image__title') ?: '');
                }
                if ($imageSource) {
                    $figure = new Figure(
                        $imageSource,
                        true,
                        null,
                        null
                    );
                }

                $headline = null;
                $headlineProperty = $document->getProperty('preview__headline')
                    ?: $document->getProperty('headline')
                    ?: null;
                if ($headlineProperty) {
                    $headline = new Headline(
                        HeadlineVariant::regular(),
                        HeadlineType::h2(),
                        Value::fromString($headlineProperty)
                    );
                }

                $text = null;
                $abstractProperty = $document->getProperty('preview__description')
                    ?: $document->getProperty('abstract')
                    ?: null;
                if ($abstractProperty) {
                    $text = new Text(
                        TextVariant::regular(),
                        Value::fromString($abstractProperty)
                    );
                }

                return new NavigationCard(
                    $figure,
                    $headline,
                    null,
                    $text,
                    new Link(
                        $this->uriService->getNodeUri($document),
                        LinkTarget::self(),
                        null,
                        Value::fromString('[mehr erfahren]')
                    )
                );
            }
        );
    }
}
