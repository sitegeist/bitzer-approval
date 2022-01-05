<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Domain\MostWanted\WantedNodeRepository;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\Card\IconCard\IconCard;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListFactory;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemFactory;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Video\VideoFactory;
use Hoerbiger\Shared\Presentation\Dummy\DummyContainer\DummyContainer;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
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
use Hoerbiger\Shared\Presentation\Block\Timeline\MileStone\MileStoneFactory;
use Hoerbiger\Shared\Presentation\Block\Timeline\TimelineFactory;
use Hoerbiger\Shared\Presentation\EnumProvider\Text\Columns;
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
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

/**
 * @Flow\Scope("singleton")
 */
final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    private CardFactory $cardFactory;

    private FigureFactory $figureFactory;

    private ImageSourceFactory $imageSourceFactory;

    private MileStoneFactory $milestoneFactory;

    private PartialFigureEclipseFactory $partialFigureEclipseFactory;

    private TimelineFactory $timelineFactory;

    private VideoFactory $videoFactory;

    private WantedNodeRepository $wantedNodeRepository;

    public function __construct(
        CardFactory $cardFactory,
        FigureFactory $figureFactory,
        ImageSourceFactory $imageSourceFactory,
        MileStoneFactory $milestoneFactory,
        PartialFigureEclipseFactory $partialFigureEclipseFactory,
        TimelineFactory $timelineFactory,
        VideoFactory $videoFactory,
        WantedNodeRepository $wantedNodeRepository
    ) {
        $this->cardFactory = $cardFactory;
        $this->figureFactory = $figureFactory;
        $this->imageSourceFactory = $imageSourceFactory;
        $this->milestoneFactory = $milestoneFactory;
        $this->partialFigureEclipseFactory = $partialFigureEclipseFactory;
        $this->timelineFactory = $timelineFactory;
        $this->videoFactory = $videoFactory;
        $this->wantedNodeRepository = $wantedNodeRepository;
    }

    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend, TraversableNodeInterface $site): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Collection.Downloads':
                return $this->forDownloadsNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.Download':
                return $this->cardFactory->forDownloadNode($contentNode);
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
            case 'Hoerbiger.HoerbigerCom:Content.MostWanted':
                return $this->forMostWantedNode($contentNode, $inBackend, $subgraph, $site);
            case 'Hoerbiger.HoerbigerCom:Content.Quotation':
                return $this->forQuotationNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.Gallery':
                return $this->forGalleryNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.GalleryImage':
                return $this->forGalleryImageNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Video':
                return $this->forVideoNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Collection.Milestones':
                return $this->forMilestonesNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.Milestone':
                return $this->milestoneFactory->forMilestone($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.MilestoneImage':
                return $this->forMilestoneImageNode($contentNode, $inBackend);
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
            case 'Hoerbiger.HoerbigerCom:Content.DownloadsTab':
                return $this->forDownloadsTabNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.Benefits':
                return $this->forBenefitsNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.Benefit':
                return $this->forBenefitNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.PeopleStories':
                return $this->forPeopleStoriesNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.PeopleStory':
                return $this->forPeopleStoryNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.SectionIntroduction':
                return $this->forSectionIntroductionNode($contentNode);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forDownloadsNode(TraversableNodeInterface $downloadsNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Grid(
                GridVariant::threeColumnsGap(),
                Collection::fromNodes(
                    $downloadsNode->findChildNodes(),
                    function(TraversableNodeInterface $node): Content {
                        return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                    }
                )
            )
        );
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
                GridVariant::threeColumnsGap(),
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

        $columns = Columns::from($textNode->getProperty('columns') ?: Columns::VALUE_MAX_TWO);

        return new ContentContainer(
            $columns->getIsMaxOne()
                ? ContentContainerVariant::tight()
                : ContentContainerVariant::regular(),
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
                        $columns->getIsMaxTwo()
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
            $this->figureFactory->forFullWidthImage(
                $imageSource,
                Editable::fromNodeProperty($imageNode, 'image__caption')
            )
        );
    }

    public function forMostWantedNode(TraversableNodeInterface $mostWantedNode, bool $inBackend, ContentContext $subgraph, TraversableNodeInterface $site): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $imageSource = $this->imageSourceFactory->forImageMixin($mostWantedNode, $inBackend);
        $figure = $imageSource
            ? $this->figureFactory->forTwoColumnGrid($imageSource)
            : null;
        $languages = $subgraph->getDimensions()['language'];
        $language = reset($languages);

        $text = new Box(
            BoxVariant::textCenter(),
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable(array_filter([
                    $inBackend || $mostWantedNode->getProperty('headline')
                        ? new Headline(
                        HeadlineVariant::important(),
                        HeadlineType::h2(),
                        Editable::fromNodeProperty($mostWantedNode, 'headline')
                    )
                        : null,
                    new Text(
                        TextVariant::lightContrast(),
                        Editable::fromNodeProperty($mostWantedNode, 'text'),
                        false
                    ),
                    new CacheSegment(
                        new Stack(
                            StackVariant::noGap(),
                            Collection::fromIterable(
                                $this->wantedNodeRepository->findMostWanted($language, $site),
                                function (TraversableNodeInterface $node): LinkInterface {
                                    return new Link(
                                        LinkVariant::label(),
                                        $this->uriService->getNodeUri($node),
                                        LinkTarget::self(),
                                        null,
                                        Value::fromString($node->getLabel())
                                    );
                                }
                            ),
                        ),
                        'Hoerbiger.HoerbigerCom:CacheSegment.MostWanted'
                    )
                ]), $identity)
            )
        );

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Box(
                BoxVariant::neutral(),
                new Grid(
                    GridVariant::twoColumns(),
                    Collection::fromIterable(array_filter([$figure, $text]), $identity)
                )
            )
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

    public function forVideoNode(TraversableNodeInterface $videoNode, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            $this->videoFactory->forVideo($videoNode, $inBackend)
        );
    }

    public function forMilestoneImageNode(TraversableNodeInterface $milestoneImageNode, bool $inBackend): SlotInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($milestoneImageNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element GalleryImage.');
        }

        return $this->figureFactory->forMileStoneImage($imageSource);
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
                    false
                )
            ]), $identity)
        );
    }

    public function forDefinitionListTabNode(TraversableNodeInterface $definitionListTabNode): SlotInterface
    {
        return (new DefinitionListFactory())->forDefinitionList($definitionListTabNode);
    }

    public function forDownloadsTabNode(TraversableNodeInterface $downloadsNode): SlotInterface
    {
        return new Grid(
            GridVariant::twoColumnsGap(),
            Collection::fromNodes(
                $downloadsNode->findChildNodes(),
                function(TraversableNodeInterface $node): Content {
                    return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                }
            )
        );
    }

    public function forMilestonesNode(TraversableNodeInterface $milestones): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regular(),
            $this->timelineFactory->forMilestones($milestones)
        );
    }

    public function forBenefitsNode(TraversableNodeInterface $benefitsNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::regular(),
            Collection::fromIterable([
                new Headline(
                    HeadlineVariant::regular(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($benefitsNode, 'headline')
                ),
                new Text(
                    TextVariant::neutralContrast(),
                    Editable::fromNodeProperty($benefitsNode, 'text'),
                    false
                ),
                new Grid(
                    GridVariant::twoColumnsGap(),
                    Collection::fromNodes(
                        $benefitsNode->findChildNodes(),
                        function(TraversableNodeInterface $node): Content {
                            return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                        }
                    )
                )
            ], $identity)
        );
    }

    public function forBenefitNode(TraversableNodeInterface $benefitNode): SlotInterface
    {
        return new IconCard(
            new Icon(
                'shared',
                $benefitNode->getProperty('icon') ?: '',
                IconSize::size_4()
            ),
            Editable::fromNodeProperty($benefitNode, 'headline'),
            null,
            Editable::fromNodeProperty($benefitNode, 'text'),
            null
        );
    }

    public function forPeopleStoriesNode(TraversableNodeInterface $peopleStoriesNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::regular(),
            Collection::fromIterable([
                new Headline(
                    HeadlineVariant::regular(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($peopleStoriesNode, 'headline')
                ),
                new Text(
                    TextVariant::neutralContrast(),
                    Editable::fromNodeProperty($peopleStoriesNode, 'text'),
                    false
                ),
                new Grid(
                    GridVariant::oneColumn(),
                    Collection::fromNodes(
                        $peopleStoriesNode->findChildNodes(),
                        function(TraversableNodeInterface $node): Content {
                            return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                        }
                    )
                )
            ], $identity)
        );
    }

    public function forPeopleStoryNode(TraversableNodeInterface $peopleStoryNode, bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };
        $imageSource = $this->imageSourceFactory->forImageMixin($peopleStoryNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element PeopleStory.');
        }

        return Collection::fromIterable([
            $this->figureFactory->forPeopleStory($imageSource),
            new Row(
                RowVariant::alternateMetalineEllipsis(),
                Collection::fromIterable([
                    Editable::fromNodeProperty($peopleStoryNode, 'metaline'),
                    Editable::fromNodeProperty($peopleStoryNode, 'tags')
                ], $identity)
            ),
            new Headline(
                HeadlineVariant::regular(),
                HeadlineType::h3(),
                Editable::fromNodeProperty($peopleStoryNode, 'headline')
            ),
            new Text(
                TextVariant::neutralContrast(),
                Editable::fromNodeProperty($peopleStoryNode, 'introduction'),
                false
            ),
            new Quotation(
                Editable::fromNodeProperty($peopleStoryNode, 'text'),
                Editable::fromNodeProperty($peopleStoryNode, 'spokenByCharacter')
            )
        ], $identity);
    }

    public function forSectionIntroductionNode(TraversableNodeInterface $sectionIntroductionNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::regular(),
            Collection::fromIterable([
                new Icon(
                    'shared',
                    $sectionIntroductionNode->getProperty('icon') ?: 'arrow-right',
                    IconSize::size_4()
                ),
                new Headline(
                    HeadlineVariant::regular(),
                    HeadlineType::h2(),
                    Editable::fromNodeProperty($sectionIntroductionNode, 'headline')
                ),
                new Text(
                    TextVariant::neutralContrast(),
                    Editable::fromNodeProperty($sectionIntroductionNode, 'text'),
                    false
                )
            ], $identity)
        );
    }
}
