<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage\Layout;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseFactory;
use Hoerbiger\Shared\Presentation\Block\Quotation\Quotation;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
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

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Content.ImageOverlappedByText':
                return $this->forImageOverLappedByTextNode($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.TileNavigation':
                return $this->forTileNavigation($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Text':
                return $this->forTextNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.TextWithImage':
                return $this->forTextWithImageNode($contentNode, $inBackend, $subgraph);
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

    private function forTileNavigation(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        $cardFactory = new CardFactory();

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::twoColumnsGap(),
                Collection::fromNodes(
                    TraversableNodes::fromArray($node->getProperty('documents') ?: []),
                    function (TraversableNodeInterface $document) use ($subgraph, $inBackend, $cardFactory)  {
                        return $cardFactory->forNavigationTile($document, $inBackend, $subgraph);
                    }
                )
            )
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
                StackVariant::xs(),
                Collection::fromIterable(array_filter([
                    new Headline(
                        HeadlineVariant::important(),
                        HeadlineType::h2(),
                        Editable::fromNodeProperty($textNode, 'headline')
                    ),
                    new Text(
                        TextVariant::lightContrast(),
                        Editable::fromNodeProperty($textNode, 'text'),
                        true
                    )
                ]), $identity)
            )
        );
    }

    public function forTextWithImageNode(TraversableNodeInterface $textWithImageNode, bool $inBackend, ContentContext $subgraph): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $figure = (new FigureFactory())->forImageMixin($textWithImageNode, $inBackend);
        if ($figure) {
            $figure = $figure->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center());
        }

        $text = new Box(
            BoxVariant::textCenter(),
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable(array_filter([
                    $inBackend || $textWithImageNode->getProperty('headline')
                        ? new Headline(
                            HeadlineVariant::important(),
                            HeadlineType::h2(),
                            Editable::fromNodeProperty($textWithImageNode, 'headline')
                        )
                        : null,
                    new Text(
                        TextVariant::lightContrast(),
                        Editable::fromNodeProperty($textWithImageNode, 'text'),
                        false
                    ),
                    (new LinkFactory())->forLinkMixin($textWithImageNode, $subgraph)
                ]), $identity)
            )
        );
        $layout = Layout::from($textWithImageNode->getProperty('layout'));

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::twoColumns(),
                $layout->getIsImageFirst()
                    ? Collection::fromIterable([$figure, $text], $identity)
                    : Collection::fromIterable([$text, $figure], $identity)
            )
        );
    }

    public function forImageNode(TraversableNodeInterface $imageNode, bool $inBackend): SlotInterface
    {
        $figure = (new FigureFactory())->forImageMixin($imageNode, $inBackend);

        if (!$figure) {
            throw new \InvalidArgumentException('Property "image" is required for Content-Element Image.');
        }

        return new ContentContainer(
            ContentContainerVariant::regular(),
            $figure
        );
    }

    public function forQuotationNode(TraversableNodeInterface $quotationNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Quotation(
                Editable::fromNodeProperty($quotationNode, 'text'),
                Editable::fromNodeProperty($quotationNode, 'spokenByCharacter')
            )
        );
    }

    public function forGalleryNode(TraversableNodeInterface $galleryNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Row(
                RowVariant::gallery(),
                Collection::fromNodes(
                    $galleryNode->findChildNodes(),
                    function(TraversableNodeInterface $galleryImage) {
                        return Content::fromNode($galleryImage, 'Hoerbiger.HoerbigerCom:ContentSlot');
                    }
                )
            )
        );
    }

    public function forGalleryImageNode(TraversableNodeInterface $galleryImageNode, bool $inBackend): SlotInterface
    {
        $figure = (new FigureFactory())->forImageMixin($galleryImageNode, $inBackend);

        if(!$figure) {
            throw new \InvalidArgumentException('Property "image" is required for Content-Element Image.');
        }

        return $figure->withObjectFitAndPosition(FigureObjectFit::contain(), FigureObjectPosition::center());
    }
}
