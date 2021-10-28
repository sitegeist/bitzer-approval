<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListFactory;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Dummy\DummyContainer\DummyContainer;
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
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxFactory;
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
    private ImageSourceFactory $imageSourceFactory;

    private CardFactory $cardFactory;

    private FigureFactory $figureFactory;

    private PartialFigureEclipseFactory $partialFigureEclipseFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        CardFactory $cardFactory,
        FigureFactory $figureFactory,
        PartialFigureEclipseFactory $partialFigureEclipseFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->cardFactory = $cardFactory;
        $this->figureFactory = $figureFactory;
        $this->partialFigureEclipseFactory = $partialFigureEclipseFactory;
    }

    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Content.DummyText':
                return $this->forDummyTextNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.DummyTextWithImage':
                return $this->forDummyTextWithImageNode($contentNode, $inBackend, $subgraph);
            case 'Hoerbiger.HoerbigerCom:Content.ImageOverlappedByText':
                return $this->forImageOverLappedByTextNode($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.TileNavigation':
                return $this->forTileNavigation($contentNode, $inBackend);
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
            case 'Hoerbiger.HoerbigerCom:Collection.DefinitionList':
                return $this->forDefinitionListNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.DefinitionItem':
                return $this->forDefinitionItemNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.TabBox':
                return $this->forTabBox($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.TextTab':
                return $this->forTextTabNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.DefinitionListTab':
                return $this->forDefinitionListTabNode($contentNode);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        /** @var SlotInterface $partialFigureEclipse */
        $partialFigureEclipse = $this->partialFigureEclipseFactory->forImageOverLappedByTextNode($node, $subgraph, $inBackend);

        return new ContentContainer(
            ContentContainerVariant::regular(),
            $partialFigureEclipse
        );
    }

    private function forTileNavigation(TraversableNodeInterface $node, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::twoColumnsGap(),
                Collection::fromNodes(
                    TraversableNodes::fromArray($node->getProperty('documents') ?: []),
                    function (TraversableNodeInterface $document) use ($inBackend)  {
                        return $this->cardFactory->forNavigationTile($document, $inBackend);
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

        $imageSource = $this->imageSourceFactory->forImageMixin($textWithImageNode, $inBackend);
        $figure = $imageSource
            ? $this->figureFactory->forTwoColumnGrid($imageSource)
            : null;

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
        $layout = Layout::from(
            $textWithImageNode->getProperty('layout')
                ?: Layout::VALUE_IMAGEFIRST
        );

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
        $imageSource = $this->imageSourceFactory->forImageMixin($imageNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element image.');
        }

        return new ContentContainer(
            ContentContainerVariant::regular(),
            $this->figureFactory->forFullWidthImage($imageSource)
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
        $imageSource = $this->imageSourceFactory->forImageMixin($galleryImageNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element GalleryImage.');
        }

        return $this->figureFactory->forGalleryImage($imageSource);
    }

    public function forDummyTextNode(TraversableNodeInterface $dummyTextNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new DummyContainer(
                $this->uriService->getResourceUri('Hoerbiger.Shared', 'Images/sample.png'),
                new Stack(
                    StackVariant::xs(),
                    Collection::fromIterable(array_filter([
                        new Headline(
                            HeadlineVariant::important(),
                            HeadlineType::h2(),
                            Editable::fromNodeProperty($dummyTextNode, 'headline')
                        ),
                        new Text(
                            TextVariant::lightContrast(),
                            Editable::fromNodeProperty($dummyTextNode, 'text'),
                            true
                        )
                    ]), $identity)
                )
            )
        );
    }

    public function forDummyTextWithImageNode(
        TraversableNodeInterface $dummyTextWithImageNode,
        bool $inBackend,
        ContentContext $subgraph
    ): SlotInterface {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $imageSource = $this->imageSourceFactory->forImageMixin($dummyTextWithImageNode, $inBackend);
        $figure = $imageSource
            ? $this->figureFactory->forTwoColumnGrid($imageSource)
            : null;

        $text = new DummyContainer(
                $this->uriService->getResourceUri('Hoerbiger.Shared', 'Images/sample.png'),
                new Box(
                BoxVariant::textCenter(),
                new Stack(
                    StackVariant::xs(),
                    Collection::fromIterable(array_filter([
                        $inBackend || $dummyTextWithImageNode->getProperty('headline')
                            ? new Headline(
                            HeadlineVariant::important(),
                            HeadlineType::h2(),
                            Editable::fromNodeProperty($dummyTextWithImageNode, 'headline')
                        )
                            : null,
                        new Text(
                            TextVariant::lightContrast(),
                            Editable::fromNodeProperty($dummyTextWithImageNode, 'text'),
                            false
                        ),
                        (new LinkFactory())->forLinkMixin($dummyTextWithImageNode, $subgraph)
                    ]), $identity)
                )
            )
        );
        $layout = Layout::from(
            $dummyTextWithImageNode->getProperty('layout')
                ?: Layout::VALUE_IMAGEFIRST
        );

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

    public function forDefinitionListNode(TraversableNodeInterface $definitionListNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Box(
                BoxVariant::decorative(),
                (new DefinitionListFactory())->forDefinitionList($definitionListNode)
            )
        );
    }

    public function forDefinitionItemNode(TraversableNodeInterface $definitionNode): SlotInterface
    {
        return (new ListItemFactory())->forDefinition($definitionNode);
    }

    public function forTabBox(TraversableNodeInterface $tabBox): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Box(
                BoxVariant::decorative(),
                (new TabBoxFactory())->forTabBox($tabBox)
            )
        );
    }

    public function forTabLabel(TraversableNodeInterface $tabNode): SlotInterface
    {
        return Editable::fromNodeProperty($tabNode, 'tab__label');
    }

    public function forTextTabNode(TraversableNodeInterface $textTabNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new Stack(
            StackVariant::xs(),
            Collection::fromIterable(array_filter([
                new Headline(
                    HeadlineVariant::regular(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($textTabNode, 'headline')
                ),
                new Text(
                    TextVariant::neutralContrast(),
                    Editable::fromNodeProperty($textTabNode, 'text'),
                    true
                )
            ]), $identity)
        );
    }

    public function forDefinitionListTabNode(TraversableNodeInterface $definitionListTabNode): SlotInterface
    {
        return (new DefinitionListFactory())->forDefinitionList($definitionListTabNode);
    }
}
