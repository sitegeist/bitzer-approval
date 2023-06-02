<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Catalog\Domain\ProductClass;
use Hoerbiger\HoerbigerCom\Domain\MostWanted\WantedNodeRepository;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonFactory;
use Hoerbiger\Shared\Presentation\Block\Card\IconCard\IconCard;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListFactory;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemFactory;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconColor;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\MatomoOptOut\MatomoOptOut;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\Video\VideoFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage\Layout;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\NewsQuotation\NewsQuotation;
use Hoerbiger\Shared\Presentation\Block\NewsQuotation\NewsQuotationVariant;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseFactory;
use Hoerbiger\Shared\Presentation\Block\PeopleStory\PeopleStory;
use Hoerbiger\Shared\Presentation\Block\Quotation\Quotation;
use Hoerbiger\Shared\Presentation\Block\Quotation\QuotationVariant;
use Hoerbiger\Shared\Presentation\Block\Tag\Tag;
use Hoerbiger\Shared\Presentation\Block\Tag\TagVariant;
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
use Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\HeaderVideoStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem\SliderItem;
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

    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend, TraversableNodeInterface $site, TraversableNodeInterface $documentNode): SlotInterface
    {
        $productClass = ProductClass::tryFrom($documentNode->getProperty('productClass'));
        $theme = $productClass ? $productClass->toTheme() : null;

        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Collection.Downloads':
                return $this->forDownloadsNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.Download':
                return $this->cardFactory->forDownloadNode($contentNode, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.ImageOverlappedByText':
                return $this->forImageOverLappedByTextNode($contentNode, $subgraph, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.TileNavigation':
                return $this->forTileNavigation($contentNode, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.Text':
                return $this->forTextNode($contentNode, $subgraph, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.TextWithMetaText':
                return $this->forTextWithMetaTextNode($contentNode, $subgraph, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.TextWithImage':
                return $this->forTextWithImageNode($contentNode, $inBackend, $subgraph, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.Image':
                return $this->forImageNode($documentNode, $contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.MostWanted':
                return $this->forMostWantedNode($contentNode, $inBackend, $subgraph, $site);
            case 'Hoerbiger.HoerbigerCom:Content.Quotation':
                return $this->forQuotationNode($contentNode, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.NewsQuotation':
                return $this->forNewsQuotationNode($contentNode, $theme);
            case 'Hoerbiger.HoerbigerCom:Collection.Gallery':
            case 'Hoerbiger.HoerbigerCom:Collection.Gallery.Square':
            case 'Hoerbiger.HoerbigerCom:Collection.Gallery.Portrait':
                return $this->forGalleryNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.GalleryImage':
            case 'Hoerbiger.HoerbigerCom:Content.GalleryImage.Square':
            case 'Hoerbiger.HoerbigerCom:Content.GalleryImage.Portrait':
                return $this->forGalleryImageNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Video':
                return $this->forVideoNode($contentNode, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Collection.Milestones':
                return $this->forMilestonesNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.Milestone':
                return $this->milestoneFactory->forMilestone($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.MilestoneImage':
                return $this->forMilestoneImageNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Collection.DefinitionList':
                return $this->forDefinitionListNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.DefinitionItem':
                // @todo how?
                return $this->forDefinitionItemNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.TabBox':
                return $this->forTabBox($contentNode, $inBackend, $theme);
            case 'Hoerbiger.HoerbigerCom:Collection.PeopleAndCultureTabBox':
                return $this->forTabBox($contentNode, $inBackend, null);
            case 'Hoerbiger.HoerbigerCom:Content.TextTab':
                return $this->forTextTabNode($contentNode, $theme);
            case 'Hoerbiger.HoerbigerCom:Content.DefinitionListTab':
                return $this->forDefinitionListTabNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.DownloadsTab':
                return $this->forDownloadsTabNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.PeopleAndCultureTextTab':
                return $this->forPeopleAndCultureTextTabNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.Benefits':
                return $this->forBenefitsNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.Benefit':
                return $this->forBenefitNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Collection.PeopleStories':
                return $this->forPeopleStoriesNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.PeopleStory':
                return $this->forPeopleStoryNode($contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.SectionIntroduction':
                return $this->forSectionIntroductionNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.CookieDeclaration':
                return $this->forCookieDeclarationNode($contentNode);
            case 'Hoerbiger.HoerbigerCom:Content.ImageWithLink':
                return $this->forImageWithLink($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Collection.PrivacyPageContent':
                return Content::fromNode($contentNode, 'Neos.Neos:ContentCollection');
            case 'Hoerbiger.HoerbigerCom:Content.MatomoOptOut':
                return $this->forMatomoOptOut($subgraph);
            case 'Hoerbiger.HoerbigerCom:Collection.HeaderVideoStage':
                return $this->forHeaderVideoStage($contentNode, $subgraph, $inBackend);
            case 'Hoerbiger.HoerbigerCom:Content.HeaderVideoStage.SliderItem':
                return $this->forHeaderVideoStageSliderItem($contentNode, $subgraph, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forDownloadsNode(TraversableNodeInterface $downloadsNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Grid(
                GridVariant::VARIANT_THREECOLUMNSGAP,
                Collection::fromNodes(
                    $downloadsNode->findChildNodes(),
                    function (TraversableNodeInterface $node): Content {
                        return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                    }
                )
            )
        );
    }

    private function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend, ?Theme $theme): SlotInterface
    {
        /** @var SlotInterface $partialFigureEclipse */
        $partialFigureEclipse = $this->partialFigureEclipseFactory->forImageOverLappedByTextNode($node, $subgraph, $inBackend, $theme);

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            $partialFigureEclipse
        );
    }

    private function forTileNavigation(TraversableNodeInterface $node, bool $inBackend, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new CacheSegment(
                new Grid(
                    GridVariant::VARIANT_THREECOLUMNSGAP,
                    Collection::fromNodes(
                        TraversableNodes::fromArray($node->getProperty('documents') ?: []),
                        function (TraversableNodeInterface $document) use ($inBackend, $theme) {
                            return $this->cardFactory->forNavigationTile($document, $inBackend, $theme);
                        }
                    )
                ),
                'Hoerbiger.HoerbigerCom:CacheSegment.TileNavigation'
            )
        );
    }

    public function forTextNode(TraversableNodeInterface $textNode, ContentContext $subgraph, bool $inBackend, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $columns = Columns::from($textNode->getProperty('columns') ?: Columns::VALUE_MAX_TWO);

        return new ContentContainer(
            $columns === Columns::VALUE_MAX_ONE
                ? ContentContainerVariant::VARIANT_TIGHT
                : ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_XS,
                Collection::fromIterable(array_filter([
                    $inBackend || $textNode->getProperty('headline')
                        ? new Headline(
                            HeadlineSize::SIZE_LARGE,
                            HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_BRAND,
                            HeadlineType::TYPE_H2,
                            Editable::fromNodeProperty($textNode, 'headline')
                        )
                        : null,
                    $inBackend || $textNode->getProperty('text')
                        ? new Text(
                            TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
                            Editable::fromNodeProperty($textNode, 'text'),
                            $columns === Columns::VALUE_MAX_TWO
                        )
                        : null,
                    $inBackend || $textNode->getProperty('link__target')
                        ? (new ButtonFactory())->forButtonMixin($textNode, $subgraph)
                        : null
                ]), $identity)
            )
        );
    }

    public function forTextWithMetaTextNode(TraversableNodeInterface $textWithMetaTextNode, ContentContext $subgraph, bool $inBackend, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::VARIANT_PRESS_LEFT_BLANK,
            new Grid(
                GridVariant::VARIANT_TWOCOLUMNSPRESS,
                Collection::fromIterable([
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_VISIBLE_MD,
                        new ContentContainer(
                            ContentContainerVariant::VARIANT_Y_CENTERED,
                            $inBackend || $textWithMetaTextNode->getProperty('meta_text')
                                ? new Stack(
                                    StackVariant::VARIANT_META_PRESS,
                                    Collection::fromIterable(array_filter([
                                        new Separator(
                                            SeparatorVariant::VARIANT_BOLD_RED,
                                            null
                                        ),
                                        new Text(
                                            TextVariant::VARIANT_SERVICE_RED,
                                            Editable::fromNodeProperty($textWithMetaTextNode, 'meta_text'),
                                            false
                                        )
                                    ]))
                                )
                                : Value::fromString('')
                        )
                    ),
                    new Stack(
                        StackVariant::VARIANT_XS,
                        Collection::fromIterable(array_filter([
                            $inBackend || $textWithMetaTextNode->getProperty('headline')
                                ? new Headline(
                                    HeadlineSize::SIZE_LARGE,
                                    HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_BRAND,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($textWithMetaTextNode, 'headline')
                            )
                                : null,
                            $inBackend || $textWithMetaTextNode->getProperty('text')
                                ? new Text(
                                    TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
                                    Editable::fromNodeProperty($textWithMetaTextNode, 'text'),
                                    false
                            )
                                : null
                        ]), $identity)
                    )]
                )
            )
        );
    }

    public function forTextWithImageNode(TraversableNodeInterface $textWithImageNode, bool $inBackend, ContentContext $subgraph, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $imageSource = $this->imageSourceFactory->forImageMixin($textWithImageNode, $inBackend);
        $figure = $imageSource
            ? $this->figureFactory->forTwoColumnGrid($imageSource)
            : null;

        $textBlockImageSource = $this->imageSourceFactory->forImageProperty(
            $textWithImageNode,
            $inBackend,
            'text__block__image'
        );

        $textBlockFigure = $textBlockImageSource
            ? $this->figureFactory->forTextBlockImage($textBlockImageSource)
            : null;

        $text = new Box(
            BoxVariant::VARIANT_TEXTCENTER,
            new Stack(
                StackVariant::VARIANT_XS,
                Collection::fromIterable(array_filter([
                    $inBackend || $textWithImageNode->getProperty('headline')
                        ? new Headline(
                            HeadlineSize::SIZE_LARGE,
                            HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_BRAND,
                            HeadlineType::TYPE_DIV,
                            Editable::fromNodeProperty($textWithImageNode, 'headline')
                        )
                        : null,
                    new Text(
                        TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
                        Editable::fromNodeProperty($textWithImageNode, 'text'),
                        false
                    ),
                    (new LinkFactory())->forLinkMixin($textWithImageNode, $subgraph, $theme),
                    $textBlockFigure
                    ? new ContentContainer(
                        ContentContainerVariant::VARIANT_HALF_WITH,
                        $textBlockFigure
                    ) : null
                ]), $identity)
            )
        );
        $layout = Layout::tryFrom($textWithImageNode->getProperty('layout') ?: '')
            ?: Layout::VALUE_IMAGEFIRST;

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Grid(
                GridVariant::VARIANT_TWOCOLUMNSNOGAP,
                $layout === Layout::VALUE_IMAGEFIRST
                    ? Collection::fromIterable([$figure, $text], $identity)
                    : Collection::fromIterable([$text, $figure], $identity)
            )
        );
    }

    public function forImageNode(
        TraversableNodeInterface $documentNode,
        TraversableNodeInterface $imageNode,
        bool $inBackend
    ): SlotInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($imageNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element image.');
        }

        $containerVariant = ContentContainerVariant::VARIANT_REGULAR;
        $image = $this->figureFactory->forFullWidthImage(
            $imageSource,
            Editable::fromNodeProperty($imageNode, 'image__caption')
        );

        if (
            $documentNode->getNodeTypeName() == 'Hoerbiger.HoerbigerCom.PressArchive:Document.HannsRelease' ||
            $documentNode->getNodeTypeName() == 'Hoerbiger.HoerbigerCom.PressArchive:Document.YearbookRelease' ||
            $documentNode->getNodeTypeName() == 'Hoerbiger.HoerbigerCom.PressArchive:Document.PressRelease'
        ) {
            $containerVariant = ContentContainerVariant::VARIANT_PRESS_IMAGE;
            $image = $this->figureFactory->forSlideStageIcon(
                $imageSource
            );
        }

        return new ContentContainer(
            $containerVariant,
            $image
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
            BoxVariant::VARIANT_TEXTCENTER,
            new Stack(
                StackVariant::VARIANT_XS,
                Collection::fromIterable(array_filter([
                    $inBackend || $mostWantedNode->getProperty('headline')
                        ? new Headline(
                            HeadlineSize::SIZE_LARGE,
                            HeadlineColor::COLOR_BRAND,
                            HeadlineType::TYPE_H2,
                            Editable::fromNodeProperty($mostWantedNode, 'headline')
                        )
                        : null,
                    new Text(
                        TextVariant::VARIANT_LIGHT_CONTRAST,
                        Editable::fromNodeProperty($mostWantedNode, 'text'),
                        false
                    ),
                    new CacheSegment(
                        new Stack(
                            StackVariant::VARIANT_NOGAP,
                            Collection::fromIterable(
                                $this->wantedNodeRepository->findMostWanted($language, $site),
                                function (TraversableNodeInterface $node): LinkInterface {
                                    return new Link(
                                        LinkVariant::VARIANT_LABEL,
                                        $this->uriService->getNodeUri($node),
                                        LinkTarget::TARGET_SELF,
                                        null,
                                        Value::fromString($node->getLabel()),
                                        null
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
            ContentContainerVariant::VARIANT_REGULAR,
            new Box(
                BoxVariant::VARIANT_NEUTRAL,
                new Grid(
                    GridVariant::VARIANT_TWOCOLUMNS,
                    Collection::fromIterable(array_filter([$figure, $text]), $identity)
                )
            )
        );
    }

    public function forQuotationNode(TraversableNodeInterface $quotationNode, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Quotation(
                QuotationVariant::forTheme($theme),
                Editable::fromNodeProperty($quotationNode, 'text'),
                Editable::fromNodeProperty($quotationNode, 'spokenByCharacter')
            )
        );
    }

    public function forNewsQuotationNode(TraversableNodeInterface $NewsquotationNode, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_PRESS,
            new ContentContainer(
                ContentContainerVariant::VARIANT_BLANKY,
                new NewsQuotation(
                    NewsQuotationVariant::VARIANT_REGULAR,
                    Editable::fromNodeProperty($NewsquotationNode, 'text'),
                    Editable::fromNodeProperty($NewsquotationNode, 'spokenByCharacter'),
                    Editable::fromNodeProperty($NewsquotationNode, 'division')
                )
            )
        );
    }

    public function forGalleryNode(TraversableNodeInterface $galleryNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Row(
                RowVariant::VARIANT_GALLERY,
                Collection::fromNodes(
                    $galleryNode->findChildNodes(),
                    function (TraversableNodeInterface $galleryImage) {
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

        return new Stack(
            StackVariant::VARIANT_XS,
            Collection::fromIterable([
                $this->figureFactory->forGalleryImage($imageSource),
                new Text(
                    TextVariant::VARIANT_DEFAULT,
                    Editable::fromNodeProperty($galleryImageNode, 'caption'),
                    false
                )
            ], fn (SlotInterface $slot): SlotInterface => $slot)
        );
    }

    public function forVideoNode(TraversableNodeInterface $videoNode, bool $inBackend, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            $this->videoFactory->forVideo($videoNode, $inBackend, $theme)
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
            ContentContainerVariant::VARIANT_REGULAR,
            new Box(
                BoxVariant::VARIANT_DECORATIVE,
                (new DefinitionListFactory())->forDefinitionList($definitionListNode)
            )
        );
    }

    public function forDefinitionItemNode(TraversableNodeInterface $definitionNode): SlotInterface
    {
        return (new ListItemFactory())->forDefinition($definitionNode);
    }

    public function forTabBox(TraversableNodeInterface $tabBox, bool $inBackend, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $identifier = "anchor-" . (string)$tabBox->getNodeAggregateIdentifier();

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Box(
                BoxVariant::tryFromTheme($theme) ?: BoxVariant::VARIANT_DECORATIVE,
                new Stack(
                    StackVariant::VARIANT_XS_STRETCH,
                    Collection::fromIterable(array_filter([
                        $inBackend || $tabBox->getProperty('headline')
                            ? new Headline(
                                HeadlineSize::SIZE_LARGE,
                                HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_BRAND,
                                HeadlineType::TYPE_DIV,
                                Editable::fromNodeProperty($tabBox, 'headline')
                            )
                            : null,
                        (new TabBoxFactory())->forTabBox($tabBox, $identifier, $theme)
                    ]), $identity)
                ),
                $identifier
            )
        );
    }

    public function forTabLabel(TraversableNodeInterface $tabNode): SlotInterface
    {
        return Editable::fromNodeProperty($tabNode, 'tab__label');
    }

    public function forTextTabNode(TraversableNodeInterface $textTabNode, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };


        return new Stack(
            StackVariant::VARIANT_XS,
            Collection::fromIterable(array_filter([
                new Headline(
                    HeadlineSize::SIZE_SMALL,
                    HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_BRAND,
                    HeadlineType::TYPE_DIV,
                    Editable::fromNodeProperty($textTabNode, 'headline')
                ),
                new Text(
                    TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_NEUTRAL_CONTRAST,
                    Editable::fromNodeProperty($textTabNode, 'text'),
                    false
                )
            ]), $identity)
        );
    }

    public function forPeopleAndCultureTextTabNode(TraversableNodeInterface $textTabNode): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $icon = $textTabNode->getProperty('icon');

        return new Stack(
            StackVariant::VARIANT_XS,
            Collection::fromIterable(array_filter([
                new Grid(
                    $icon ? GridVariant::VARIANT_ICONLEFT : GridVariant::VARIANT_ONECOLUMN,
                    Collection::fromIterable(array_filter([
                        $icon ?
                            new Tag(
                            'span',
                            TagVariant::VARIANT_TRANSPARENT,
                            Icon::fromStampedeDataSourceValue(
                                $textTabNode->getProperty('icon') ?: 'peopleAndCulture:faqs',
                                IconSize::SIZE_SIZE_3
                                )
                            )
                            : null,
                        new Headline(
                            HeadlineSize::SIZE_SMALL,
                            HeadlineColor::COLOR_BRAND,
                            HeadlineType::TYPE_DIV,
                            Editable::fromNodeProperty($textTabNode, 'headline')
                        ),
                    ]), $identity)
                ),
                new Text(
                    TextVariant::VARIANT_NEUTRAL_CONTRAST,
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
            GridVariant::VARIANT_TWOCOLUMNSGAP,
            Collection::fromNodes(
                $downloadsNode->findChildNodes(),
                function (TraversableNodeInterface $node): Content {
                    return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                }
            )
        );
    }

    public function forMilestonesNode(TraversableNodeInterface $milestones): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            $this->timelineFactory->forMilestones($milestones)
        );
    }

    public function forBenefitsNode(TraversableNodeInterface $benefitsNode, bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM_STRETCH,
                Collection::fromIterable(array_filter([
                    new Stack(
                        StackVariant::VARIANT_XS,
                        Collection::fromIterable(array_filter([
                            $inBackend || $benefitsNode->getProperty('headline')
                                ? new Headline(
                                    HeadlineSize::SIZE_LARGE,
                                    HeadlineColor::COLOR_BRAND,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($benefitsNode, 'headline')
                                )
                                : null,
                            $inBackend || $benefitsNode->getProperty('text')
                                ? new Text(
                                    TextVariant::VARIANT_NEUTRAL_CONTRAST,
                                    Editable::fromNodeProperty($benefitsNode, 'text'),
                                    false
                                )
                                : null,
                        ]), $identity),
                    ),
                    new Grid(
                        GridVariant::VARIANT_THREECOLUMNSNARROWGAP,
                        Collection::fromNodes(
                            $benefitsNode->findChildNodes(),
                            function (TraversableNodeInterface $node): Content {
                                return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                            }
                        )
                    )
                ]), $identity)
            )
        );
    }

    public function forBenefitNode(TraversableNodeInterface $benefitNode): SlotInterface
    {
        return new IconCard(
            Icon::fromStampedeDataSourceValue(
                $benefitNode->getProperty('icon') ?: 'benefits:responsibility',
                IconSize::SIZE_PARENT,
                IconColor::COLOR_WHITE
            ),
            Editable::fromNodeProperty($benefitNode, 'headline'),
            null,
            new Text(
                TextVariant::VARIANT_NEUTRAL_CONTRAST,
                Editable::fromNodeProperty($benefitNode, 'text'),
                false
            ),
            null
        );
    }

    public function forPeopleStoriesNode(TraversableNodeInterface $peopleStoriesNode, bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM_STRETCH,
                Collection::fromIterable(array_filter([
                    new Stack(
                        StackVariant::VARIANT_XS,
                        Collection::fromIterable(array_filter([
                            $inBackend || $peopleStoriesNode->getProperty('headline')
                                ? new Headline(
                                    HeadlineSize::SIZE_LARGE,
                                    HeadlineColor::COLOR_BRAND,
                                    HeadlineType::TYPE_H2,
                                    Editable::fromNodeProperty($peopleStoriesNode, 'headline')
                                )
                                : null,
                            $inBackend || $peopleStoriesNode->getProperty('text')
                                ? new Text(
                                    TextVariant::VARIANT_NEUTRAL_CONTRAST,
                                    Editable::fromNodeProperty($peopleStoriesNode, 'text'),
                                    false
                                )
                                : null,
                        ]), $identity),
                    ),
                    Collection::fromNodes(
                        $peopleStoriesNode->findChildNodes(),
                        function (TraversableNodeInterface $node): Content {
                            return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                        }
                    )
                ]), $identity)
            )
        );
    }

    public function forPeopleStoryNode(TraversableNodeInterface $peopleStoryNode, bool $inBackend): SlotInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($peopleStoryNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element PeopleStory.');
        }

        return new PeopleStory(
            $this->figureFactory->forPeopleStory($imageSource),
            new Headline(
                HeadlineSize::SIZE_LARGE,
                HeadlineColor::COLOR_TEXT,
                HeadlineType::TYPE_DIV,
                Editable::fromNodeProperty($peopleStoryNode, 'headline')
            ),
            $inBackend || $peopleStoryNode->getProperty('introduction')
                ? new Text(
                    TextVariant::VARIANT_NEUTRAL_CONTRAST,
                    Editable::fromNodeProperty($peopleStoryNode, 'introduction'),
                    false
                )
                : null,
            $inBackend || $peopleStoryNode->getProperty('text')
                ? new Quotation(
                    QuotationVariant::VARIANT_INLINE,
                    Editable::fromNodeProperty($peopleStoryNode, 'text'),
                    Editable::fromNodeProperty($peopleStoryNode, 'spokenByCharacter')
                )
                : null,
            Editable::fromNodeProperty($peopleStoryNode, 'metaline'),
            Editable::fromNodeProperty($peopleStoryNode, 'tags')
        );
    }

    public function forSectionIntroductionNode(TraversableNodeInterface $sectionIntroductionNode): SlotInterface
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR_WITHOUT_BOTTOM,
            new Stack(
                StackVariant::VARIANT_XS_STRETCH,
                Collection::fromIterable([
                    new Grid(
                        GridVariant::VARIANT_ICONLEFT,
                        Collection::fromIterable(array_filter([
                            new Tag(
                                'span',
                                TagVariant::VARIANT_TRANSPARENT,
                                Icon::fromStampedeDataSourceValue(
                                    $sectionIntroductionNode->getProperty('icon') ?: 'peopleCulture:faqs',
                                    IconSize::SIZE_SIZE_4
                                ),
                            ),
                            new Headline(
                                HeadlineSize::SIZE_LARGE,
                                HeadlineColor::COLOR_BRAND,
                                HeadlineType::TYPE_H2,
                                Editable::fromNodeProperty($sectionIntroductionNode, 'headline')
                            ),
                        ]), $identity)
                    ),
                    new Text(
                        TextVariant::VARIANT_NEUTRAL_CONTRAST,
                        Editable::fromNodeProperty($sectionIntroductionNode, 'text'),
                        false
                    )
                ], $identity)
            )
        );
    }

    public function forCookieDeclarationNode(TraversableNodeInterface $cookieDeclarationNode): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            Value::fromString('<script id="CookieDeclaration" src="https://consent.cookiebot.com/aa81a090-a42f-459c-9c12-35f852ed4015/cd.js" type="text/javascript" async></script>')
        );
    }

    public function forImageWithLink(TraversableNodeInterface $imageWithLinkNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        $identity = fn (SlotInterface $slot): SlotInterface => $slot;

        $link = (new LinkFactory())->forLinkMixin($imageWithLinkNode, $subgraph, null);

        $imageSource = $this->imageSourceFactory->forImageMixin($imageWithLinkNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element image.');
        }
        $figure = $this->figureFactory->forFullWidthImage(
            $imageSource,
            Editable::fromNodeProperty($imageWithLinkNode, 'image__caption')
        );

        return new ContentContainer(
            ContentContainerVariant::VARIANT_BLANK,
            Collection::fromIterable([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULAR,
                    new Grid(
                        GridVariant::VARIANT_TWOCOLUMNSFRMAXGAP,
                        Collection::fromIterable(array_filter([
                            new Headline(
                                HeadlineSize::SIZE_LARGE,
                                HeadlineColor::COLOR_BRAND,
                                HeadlineType::TYPE_H2,
                                Editable::fromNodeProperty($imageWithLinkNode, 'headline')
                            ),
                            $link
                        ]), $identity)
                    )
                ),
                $link
                    ? $link->asContainer()->withContent($figure)
                    : $figure
            ],$identity)
        );
    }

    private function forMatomoOptOut(ContentContext $subgraph): SlotInterface
    {
        $languages = $subgraph->getDimensions()['language'];

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            MatomoOptOut::forLocale(reset($languages))
        );
    }

    private function forHeaderVideoStage(TraversableNodeInterface $videoStageNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_FULL_VIEW_HEIGHT,
            new HeaderVideoStage(
                Collection::fromNodes(
                    $videoStageNode->findChildNodes(),
                    function (TraversableNodeInterface $sliderItem): Content {
                        return Content::fromNode($sliderItem, 'Hoerbiger.HoerbigerCom:ContentSlot');
                    }
                )
            ),
        );
    }

    private function forHeaderVideoStageSliderItem(TraversableNodeInterface $sliderItemNode, ContentContext $subgraph, bool $inBackend): SlotInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($sliderItemNode, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Property "image" is required for content element image.');
        }

        $figure = $this->figureFactory->forPartialEclipse(
            $imageSource
        );

        $text = new Grid(
            GridVariant::VARIANT_ICONLEFT,
            Collection::fromIterable([
                new Tag(
                    'div',
                    TagVariant::from('heightFull'),
                    new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1_5)
                ),
                new Text(
                    TextVariant::VARIANT_BRAND_CONTRAST_BOLD,
                    Editable::fromNodeProperty($sliderItemNode, "text"),
                    false
                )
            ], function (SlotInterface $slot) {
                return $slot;
            })
        );

        return new SliderItem(
            $sliderItemNode->getProperty("headline") || $inBackend
                ? new Headline(
                    HeadlineSize::SIZE_MEDIUM_ITALIC,
                    HeadlineColor::COLOR_WHITE,
                    HeadlineType::TYPE_DIV,
                    Editable::fromNodeProperty($sliderItemNode, "headline")
                )
                : null,
            $sliderItemNode->getProperty("link__target") && !$inBackend
                ? (new LinkFactory())->forLinkWithContent($sliderItemNode, $subgraph, $text)
                : $text,
            $figure,
            $this->videoFactory->forSliderVideo($sliderItemNode, $inBackend, Theme::VALUE_SLIDER)
        );
    }
}
