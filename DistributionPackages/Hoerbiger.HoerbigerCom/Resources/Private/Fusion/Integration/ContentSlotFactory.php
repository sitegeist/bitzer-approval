<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage\Layout;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
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
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
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
            case 'Hoerbiger.HoerbigerCom:Content.Text':
                return $this->forTextNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.TextWithImage':
                return $this->forTextWithImageNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Image':
                return $this->forImageNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Quotation':
                return $this->forQuotationNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.Gallery':
                return $this->forGalleryNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.GalleryImage':
                return $this->forGalleryImageNode($contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        /** @var SlotInterface $partialFigureEclipse */
        $partialFigureEclipse = (new PartialFigureEclipseFactory())->forImageOverLappedByTextNode($node, $subgraph, $inBackend);

        return new ContentContainer(
            ContentContainerVariant::regular(),
            $partialFigureEclipse
        );
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
                        TextVariant::lightContrast(),
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

    public function forTextNode(TraversableNodeInterface $textNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Stack(
                StackVariant::regular(),
                Collection::fromIterable([
                    new Headline(
                        HeadlineVariant::regular(),
                        HeadlineType::h2(),
                        Editable::fromNodeProperty($textNode,'headline')
                    ),
                    new Text(
                        TextVariant::neutralContrast(),
                        Editable::fromNodeProperty($textNode,'text')
                    )
                ], $identity)
            )
        );
    }

    public function forTextWithImageNode(TraversableNodeInterface $textWithImageNode, bool $inBackend): SlotInterface
    {

        $figure = (new FigureFactory())->forImageMixin($textWithImageNode, $inBackend);
        $layout = Layout::from($textWithImageNode->getProperty('layout'));

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return Collection::fromIterable([
            $figure,
            new Headline(
                HeadlineVariant::regular(),
                HeadlineType::h2(),
                Editable::fromNodeProperty($textWithImageNode,'headline')
            ),
            new Text(
                TextVariant::neutralContrast(),
                Editable::fromNodeProperty($textWithImageNode,'text')
            )
        ], $identity);
    }

    public function forImageNode(TraversableNodeInterface $imageNode, bool $inBackend): SlotInterface
    {
        $figure = (new FigureFactory())->forImageMixin($imageNode, $inBackend);

        if (!$figure) {
            throw new \InvalidArgumentException('Property "image" is required for Content-Element Image.');
        }

        return new ContentContainer(
            ContentContainerVariant::blank(),
            $figure
        );
    }

    public function forQuotationNode(TraversableNodeInterface $quotationNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return Collection::fromIterable([
            Editable::fromNodeProperty($quotationNode, 'text'),
            Editable::fromNodeProperty($quotationNode, 'spokenByCharacter')
        ], $identity);
    }

    public function forGalleryNode(TraversableNodeInterface $galleryNode): SlotInterface
    {
        return Collection::fromNodes(
            $galleryNode->findChildNodes(),
            function(TraversableNodeInterface $galleryImage) {
                return Content::fromNode($galleryImage, 'Hoerbiger.HoerbigerCom:ContentSlot');
            }
        );
    }

    public function forGalleryImageNode(TraversableNodeInterface $galleryImageNode, bool $inBackend): SlotInterface
    {
        $figure = (new FigureFactory())->forImageMixin($galleryImageNode, $inBackend);

        if(!$figure) {
            throw new \InvalidArgumentException('Property "image" is required for Content-Element Image.');
        }

        return $figure;
    }
}
