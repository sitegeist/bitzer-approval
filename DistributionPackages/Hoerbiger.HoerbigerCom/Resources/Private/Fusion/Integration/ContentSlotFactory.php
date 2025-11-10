<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Domain\ProductClass;
use Hoerbiger\HoerbigerCom\Domain\MostWanted\WantedNodeRepository;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\AccordionItem\AccordionItem;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonFactory;
use Hoerbiger\Shared\Presentation\Block\Card\IconCard\IconCard;
use Hoerbiger\Shared\Presentation\Block\CookieDeclaration\CookieDeclaration;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\DefinitionListFactory;
use Hoerbiger\Shared\Presentation\Block\DefinitionList\ListItem\ListItemFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconColor;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\MatomoOptOut\MatomoOptOut;
use Hoerbiger\Shared\Presentation\Block\NewsQuotation\NewsQuotation;
use Hoerbiger\Shared\Presentation\Block\NewsQuotation\NewsQuotationVariant;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseFactory;
use Hoerbiger\Shared\Presentation\Block\PeopleStory\PeopleStory;
use Hoerbiger\Shared\Presentation\Block\Quotation\Quotation;
use Hoerbiger\Shared\Presentation\Block\Quotation\QuotationVariant;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\Tag\Tag;
use Hoerbiger\Shared\Presentation\Block\Tag\TagVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Block\Timeline\MileStone\MileStoneFactory;
use Hoerbiger\Shared\Presentation\Block\Timeline\TimelineFactory;
use Hoerbiger\Shared\Presentation\Block\Video\VideoFactory;
use Hoerbiger\Shared\Presentation\EnumProvider\Text\Columns;
use Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage\Layout;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\HeaderVideoStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\HeaderVideoStage\SliderItem\SliderItem;
use Hoerbiger\Shared\Presentation\Layout\TabBox\TabBoxFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CacheSegment;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

#[Flow\Scope('singleton')]
final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly CardFactory $cardFactory,
        private readonly FigureFactory $figureFactory,
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly MileStoneFactory $milestoneFactory,
        private readonly PartialFigureEclipseFactory $partialFigureEclipseFactory,
        private readonly TimelineFactory $timelineFactory,
        private readonly VideoFactory $videoFactory,
        private readonly WantedNodeRepository $wantedNodeRepository,
        private readonly LinkFactory $linkFactory,
        private readonly CsvPOTranslator $csvPOTranslator,
    ) {
    }

    public function forContentNode(
        TraversableNodeInterface $contentNode,
        ContentContext $subgraph,
        bool $inBackend,
        TraversableNodeInterface $site,
        TraversableNodeInterface $documentNode
    ): SlotInterface {
        $productClass = ProductClass::tryFrom($documentNode->getProperty('productClass'));
        $theme = $productClass?->toTheme();

        return match ((string)$contentNode->getNodeTypeName()) {
            'Hoerbiger.HoerbigerCom:Collection.Downloads' => $this->forDownloadsNode($contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.Download' => $this->cardFactory->forDownloadNode($contentNode, $theme),
            'Hoerbiger.HoerbigerCom:Content.ImageOverlappedByText' => $this->forImageOverLappedByTextNode(
                $contentNode,
                $subgraph,
                $inBackend,
                $theme
            ),
            'Hoerbiger.HoerbigerCom:Content.TileNavigation' => $this->forTileNavigation(
                $contentNode,
                $inBackend,
                $theme
            ),
            'Hoerbiger.HoerbigerCom:Content.Text' => $this->forTextNode($contentNode, $subgraph, $inBackend, $theme),
            'Hoerbiger.HoerbigerCom:Content.TextWithMetaText' => $this->forTextWithMetaTextNode(
                $contentNode,
                $subgraph,
                $inBackend,
                $theme
            ),
            'Hoerbiger.HoerbigerCom:Content.TextWithImage' => $this->forTextWithImageNode(
                $contentNode,
                $inBackend,
                $subgraph,
                $theme
            ),
            'Hoerbiger.HoerbigerCom:Content.Image' => $this->forImageNode($documentNode, $contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.MostWanted' => $this->forMostWantedNode(
                $contentNode,
                $inBackend,
                $subgraph,
                $site
            ),
            'Hoerbiger.HoerbigerCom:Content.Quotation' => $this->forQuotationNode($contentNode, $theme, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.NewsQuotation' => $this->forNewsQuotationNode($contentNode, $theme),
            'Hoerbiger.HoerbigerCom:Collection.Gallery', 'Hoerbiger.HoerbigerCom:Collection.Gallery.Square', 'Hoerbiger.HoerbigerCom:Collection.Gallery.Portrait' => $this->forGalleryNode(
                $contentNode,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Content.GalleryImage', 'Hoerbiger.HoerbigerCom:Content.GalleryImage.Square', 'Hoerbiger.HoerbigerCom:Content.GalleryImage.Portrait' => $this->forGalleryImageNode(
                $contentNode,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Content.Video' => $this->forVideoNode($contentNode, $inBackend, $theme),
            'Hoerbiger.HoerbigerCom:Collection.Milestones' => $this->forMilestonesNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.Milestone' => $this->milestoneFactory->forMilestone($contentNode),
            'Hoerbiger.HoerbigerCom:Content.MilestoneImage' => $this->forMilestoneImageNode($contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Collection.DefinitionList' => $this->forDefinitionListNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.DefinitionItem' => $this->forDefinitionItemNode($contentNode),
            'Hoerbiger.HoerbigerCom:Collection.TabBox' => $this->forTabBox($contentNode, $inBackend, $theme),
            'Hoerbiger.HoerbigerCom:Collection.PeopleAndCultureTabBox' => $this->forTabBox(
                $contentNode,
                $inBackend,
                null
            ),
            'Hoerbiger.HoerbigerCom:Content.TextTab' => $this->forTextTabNode($contentNode, $inBackend, $theme),
            'Hoerbiger.HoerbigerCom:Content.DefinitionListTab' => $this->forDefinitionListTabNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.DownloadsTab' => $this->forDownloadsTabNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.PeopleAndCultureTextTab' => $this->forPeopleAndCultureTextTabNode(
                $contentNode
            ),
            'Hoerbiger.HoerbigerCom:Collection.Benefits' => $this->forBenefitsNode($contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.Benefit' => $this->forBenefitNode($contentNode),
            'Hoerbiger.HoerbigerCom:Collection.PeopleStories' => $this->forPeopleStoriesNode($contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.PeopleStory' => $this->forPeopleStoryNode($contentNode, $inBackend),
            'Hoerbiger.HoerbigerCom:Content.SectionIntroduction' => $this->forSectionIntroductionNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.CookieDeclaration' => $this->forCookieDeclarationNode($contentNode),
            'Hoerbiger.HoerbigerCom:Content.ImageWithLink' => $this->forImageWithLink(
                $contentNode,
                $subgraph,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Collection.PrivacyPageContent' => Content::fromNode(
                $contentNode,
                'Neos.Neos:ContentCollection'
            ),
            'Hoerbiger.HoerbigerCom:Content.MatomoOptOut' => $this->forMatomoOptOut($subgraph),
            'Hoerbiger.HoerbigerCom:Collection.HeaderVideoStage' => $this->forHeaderVideoStage(
                $contentNode,
                $subgraph,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Content.HeaderVideoStage.SliderItem' => $this->forHeaderVideoStageSliderItem(
                $contentNode,
                $subgraph,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Content.Accordion' => $this->forAccordionNode(
                $contentNode,
                $subgraph,
                $inBackend
            ),
            'Hoerbiger.HoerbigerCom:Content.AccordionItem' => $this->forAccordionItemNode(
                $contentNode,
                $subgraph,
                $inBackend
            ),
            default => throw new \InvalidArgumentException(
                'Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName()
            ),
        };
    }

    private function forDownloadsNode(TraversableNodeInterface $downloadsNode, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $downloadsNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($downloadsNode, 'metaHeadline')
                    ) : null,
                    new Grid(
                        GridVariant::VARIANT_THREECOLUMNSGAP,
                        Collection::fromNodes(
                            $downloadsNode->findChildNodes(),
                            function (TraversableNodeInterface $node): Content {
                                return Content::fromNode($node, 'Hoerbiger.HoerbigerCom:ContentSlot');
                            }
                        )
                    )
                ]))
            )
        );
    }

    private function forImageOverLappedByTextNode(
        TraversableNodeInterface $node,
        ContentContext $subgraph,
        bool $inBackend,
        ?Theme $theme
    ): SlotInterface {
        $partialFigureEclipse = $this->partialFigureEclipseFactory->forImageOverLappedByTextNode(
            $node,
            $subgraph,
            $inBackend,
            $theme
        );

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $node->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($node, 'metaHeadline')
                    ) : null,
                    $partialFigureEclipse
                ]))
            )
        );
    }

    private function forTileNavigation(TraversableNodeInterface $node, bool $inBackend, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $node->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($node, 'metaHeadline')
                    ) : null,
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
                ]))
            )
        );
    }

    public function forTextNode(
        TraversableNodeInterface $textNode,
        ContentContext $subgraph,
        bool $inBackend,
        ?Theme $theme
    ): SlotInterface {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $columns = Columns::from($textNode->getProperty('columns') ?: Columns::VALUE_MAX_TWO);

        $headline = new Headline(
            $theme ? HeadlineSize::SIZE_LARGE : HeadlineSize::SIZE_MEDIUM_ITALIC,
            HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
            HeadlineType::TYPE_H2,
            Editable::fromNodeProperty($textNode, 'headline')
        );

        $text = new Text(
            TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
            Editable::fromNodeProperty($textNode, 'text'),
            $columns === Columns::VALUE_MAX_TWO,
            $textNode->getProperty('hasStickyTableHeader')
        );

        if ($columns === Columns::VALUE_MAX_ONE_HEADLINE_LEFT_SIDE) {
            $content[] = new Grid(
                GridVariant::VARIANT_TWOCOLUMNSTHIRDBIGGAP,
                Collection::fromIterable(array_filter([
                    $headline,
                    Collection::fromIterable(array_filter([
                        $text,
                        $inBackend || $textNode->getProperty('link__target')
                            ? (new ButtonFactory())->forButtonMixin($textNode, $subgraph)
                            : null
                    ]), $identity)
                ]), $identity)
            );
        } else {
            if ($columns === Columns::VALUE_MAX_ONE_HEADLINE_TEXT_SMALL) {
                $content = [
                    $inBackend || $textNode->getProperty('headline')
                        ? $headline
                        : null,
                    $inBackend || $textNode->getProperty('text')
                        ? new ContentContainer(
                        ContentContainerVariant::VARIANT_SMALL_TEXT,
                        $text
                    )
                        : null,
                    $inBackend || $textNode->getProperty('link__target')
                        ? (new ButtonFactory())->forButtonMixin($textNode, $subgraph)
                        : null
                ];
            } else {
                $content = [
                    $inBackend || $textNode->getProperty('headline')
                        ? $headline
                        : null,
                    $inBackend || $textNode->getProperty('text')
                        ? $text
                        : null,
                    $inBackend || $textNode->getProperty('link__target')
                        ? (new ButtonFactory())->forButtonMixin($textNode, $subgraph)
                        : null
                ];
            }
        }

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $textNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($textNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        $columns === Columns::VALUE_MAX_ONE
                            ? ContentContainerVariant::VARIANT_TIGHTX
                            : ContentContainerVariant::VARIANT_BLANK,
                        new Stack(
                            StackVariant::VARIANT_XS,
                            Collection::fromIterable(
                                array_filter(
                                    $content
                                ),
                                $identity
                            )
                        )
                    )
                ]))
            )
        );
    }

    public function forTextWithMetaTextNode(
        TraversableNodeInterface $textWithMetaTextNode,
        ContentContext $subgraph,
        bool $inBackend,
        ?Theme $theme
    ): SlotInterface {
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
                                    $theme ? HeadlineSize::SIZE_LARGE : HeadlineSize::SIZE_MEDIUM_ITALIC,
                                    HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
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
                        )
                    ]
                )
            )
        );
    }

    public function forTextWithImageNode(
        TraversableNodeInterface $textWithImageNode,
        bool $inBackend,
        ContentContext $subgraph,
        ?Theme $theme
    ): SlotInterface {
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
                        $theme ? HeadlineSize::SIZE_LARGE : HeadlineSize::SIZE_MEDIUM_ITALIC,
                        HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                        HeadlineType::TYPE_DIV,
                        Editable::fromNodeProperty($textWithImageNode, 'headline')
                    )
                        : null,
                    new Text(
                        TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
                        Editable::fromNodeProperty($textWithImageNode, 'text'),
                        false
                    ),
                    $this->linkFactory->forLinkMixin($textWithImageNode, $subgraph, $theme),
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
            Collection::fromIterable(array_filter([
                $inBackend || $textWithImageNode->getProperty('metaHeadline')
                    ? new Separator(
                    SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                    Editable::fromNodeProperty($textWithImageNode, 'metaHeadline')
                ) : null,
                new Grid(
                    GridVariant::VARIANT_TWOCOLUMNSNOGAP,
                    $layout === Layout::VALUE_IMAGEFIRST
                        ? Collection::fromIterable([$figure, $text], $identity)
                        : Collection::fromIterable([$text, $figure], $identity)
                )
            ]))
        );
    }

    public function forImageNode(
        TraversableNodeInterface $documentNode,
        TraversableNodeInterface $imageNode,
        bool $inBackend
    ): SlotInterface {
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
            return new ContentContainer(
                ContentContainerVariant::VARIANT_PRESS_IMAGE,
                $this->figureFactory->forSlideStageIcon($imageSource)
            );
        }

        return new ContentContainer(
            $containerVariant,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $imageNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($imageNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_TIGHTX,
                        $image
                    )
                ]))
            )
        );
    }

    public function forMostWantedNode(
        TraversableNodeInterface $mostWantedNode,
        bool $inBackend,
        ContentContext $subgraph,
        TraversableNodeInterface $site
    ): SlotInterface {
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
                                function (TraversableNodeInterface $node): Link {
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
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $mostWantedNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($mostWantedNode, 'metaHeadline')
                    ) : null,
                    new Box(
                        BoxVariant::VARIANT_NEUTRAL,
                        new Grid(
                            GridVariant::VARIANT_TWOCOLUMNS,
                            Collection::fromIterable(array_filter([$figure, $text]), $identity)
                        )
                    )
                ]))
            )
        );
    }

    public function forQuotationNode(
        TraversableNodeInterface $quotationNode,
        ?Theme $theme,
        bool $inBackend
    ): SlotInterface {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $quotationNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($quotationNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_TIGHTX,
                        new Quotation(
                            QuotationVariant::VARIANT_REGULAR,
                            Editable::fromNodeProperty($quotationNode, 'text'),
                            Editable::fromNodeProperty($quotationNode, 'spokenByCharacter')
                        )
                    )
                ]))
            )
        );
    }

    public function forNewsQuotationNode(TraversableNodeInterface $NewsquotationNode, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_PRESS,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $NewsquotationNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($NewsquotationNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_BLANKY,
                        new NewsQuotation(
                            NewsQuotationVariant::VARIANT_REGULAR,
                            Editable::fromNodeProperty($NewsquotationNode, 'text'),
                            Editable::fromNodeProperty($NewsquotationNode, 'spokenByCharacter'),
                            Editable::fromNodeProperty($NewsquotationNode, 'division')
                        )
                    )
                ]))
            ),
        );
    }

    public function forGalleryNode(TraversableNodeInterface $galleryNode, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $galleryNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($galleryNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_TIGHTX,
                        new Row(
                            RowVariant::VARIANT_GALLERY,
                            Collection::fromNodes(
                                $galleryNode->findChildNodes(),
                                function (TraversableNodeInterface $galleryImage) {
                                    return Content::fromNode($galleryImage, 'Hoerbiger.HoerbigerCom:ContentSlot');
                                }
                            )
                        )
                    )
                ]))
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
            StackVariant::VARIANT_PX16,
            Collection::fromIterable([
                $this->figureFactory->forGalleryImage($imageSource),
                new Text(
                    TextVariant::VARIANT_NEUTRAL_SMALL,
                    Editable::fromNodeProperty($galleryImageNode, 'caption'),
                    false
                )
            ], fn(SlotInterface $slot): SlotInterface => $slot)
        );
    }

    public function forVideoNode(TraversableNodeInterface $videoNode, bool $inBackend, ?Theme $theme): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $videoNode->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($videoNode, 'metaHeadline')
                    ) : null,
                    new ContentContainer(
                        ContentContainerVariant::VARIANT_TIGHTX,
                        $this->videoFactory->forVideo($videoNode, $inBackend, $theme)
                    )
                ]))
            )
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
        $headline = $inBackend || $tabBox->getProperty('headline')
            ? new Headline(
                $theme ? HeadlineSize::SIZE_LARGE : HeadlineSize::SIZE_MEDIUM_ITALIC,
                HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                HeadlineType::TYPE_DIV,
                Editable::fromNodeProperty($tabBox, 'headline')
            )
            : null;

        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $tabBox->getProperty('metaHeadline')
                        ? new Separator(
                        SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                        Editable::fromNodeProperty($tabBox, 'metaHeadline')
                    ) : null,
                    new Box(
                        BoxVariant::tryFromTheme($theme) ?: BoxVariant::VARIANT_DECORATIVE,
                        new Stack(
                            StackVariant::VARIANT_XS_STRETCH,
                            Collection::fromIterable(array_filter([
                                $theme ? $headline : null,
                                (new TabBoxFactory())->forTabBox(
                                    $theme ? null : $headline,
                                    $tabBox,
                                    $identifier,
                                    $theme
                                ),
                            ]))
                        ),
                        $identifier
                    )
                ]))
            )
        );
    }

    public function forTabLabel(TraversableNodeInterface $tabNode): SlotInterface
    {
        return Editable::fromNodeProperty($tabNode, 'tab__label');
    }

    public function forTextTabNode(TraversableNodeInterface $textTabNode, bool $inBackend, ?Theme $theme): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };


        return new Stack(
            StackVariant::VARIANT_XS,
            Collection::fromIterable(array_filter([
                $inBackend || $textTabNode->getProperty('headline')
                    ? new Headline(
                    $theme ? HeadlineSize::SIZE_LARGE : HeadlineSize::SIZE_MEDIUM_ITALIC,
                    HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                    HeadlineType::TYPE_DIV,
                    Editable::fromNodeProperty($textTabNode, 'headline')
                )
                    : null,
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
                                TagVariant::VARIANT_NEUTRAL_CONTRAST,
                                Icon::fromStampedeDataSourceValue(
                                    $textTabNode->getProperty('icon') ?: 'peopleAndCulture:faqs',
                                    IconSize::SIZE_SIZE_3
                                )
                            )
                            : null,
                        new Headline(
                            HeadlineSize::SIZE_MEDIUM_ITALIC,
                            HeadlineColor::COLOR_NEUTRAL_CONTRAST,
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
            GridVariant::VARIANT_ONECOLUMN_NOGAP_BORDER,
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
                                HeadlineSize::SIZE_MEDIUM_ITALIC,
                                HeadlineColor::COLOR_NEUTRAL_CONTRAST,
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
                                HeadlineSize::SIZE_MEDIUM_ITALIC,
                                HeadlineColor::COLOR_NEUTRAL_CONTRAST,
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
        $identity = fn(SlotInterface $slot): SlotInterface => $slot;

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
                                TagVariant::VARIANT_NEUTRAL_CONTRAST,
                                Icon::fromStampedeDataSourceValue(
                                    $sectionIntroductionNode->getProperty('icon') ?: 'peopleCulture:faqs',
                                    IconSize::SIZE_SIZE_4
                                ),
                            ),
                            new Headline(
                                HeadlineSize::SIZE_MEDIUM_ITALIC,
                                HeadlineColor::COLOR_NEUTRAL_CONTRAST,
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
            new CookieDeclaration(
                $this->csvPOTranslator->getLabels(CookieDeclaration::class)
            )
        );
    }

    public function forImageWithLink(
        TraversableNodeInterface $imageWithLinkNode,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
        $identity = fn(SlotInterface $slot): SlotInterface => $slot;

        $link = $this->linkFactory->forLinkMixin($imageWithLinkNode, $subgraph, null);

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
                    new Stack(
                        StackVariant::VARIANT_SM,
                        Collection::fromIterable(array_filter([
                            $inBackend || $imageWithLinkNode->getProperty('metaHeadline')
                                ? new Separator(
                                SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                                Editable::fromNodeProperty($imageWithLinkNode, 'metaHeadline')
                            ) : null,
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
                        ]))
                    )
                ),
                $link
                    ? $link->asContainer()->withContent($figure)
                    : $figure
            ], $identity)
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

    private function forHeaderVideoStage(
        TraversableNodeInterface $videoStageNode,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
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

    private function forHeaderVideoStageSliderItem(
        TraversableNodeInterface $sliderItemNode,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface {
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
                ? $this->linkFactory->forLinkWithContent($sliderItemNode, $subgraph, $text)
                : $text,
            $figure,
            $this->videoFactory->forSliderVideo($sliderItemNode, $inBackend, Theme::VALUE_SLIDER)
        );
    }

    public function forAccordionNode(
        TraversableNodeInterface $accordionNode,
        ContentContext $subgraph,
        bool $inBackend,
    ): SlotInterface {
        return new ContentContainer(
            ContentContainerVariant::VARIANT_REGULAR,
            new Stack(
                StackVariant::VARIANT_SM,
                Collection::fromIterable(array_filter([
                    $inBackend || $accordionNode->getProperty('headline')
                        ? new Headline(
                            HeadlineSize::SIZE_MEDIUM_ITALIC,
                            HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                            HeadlineType::TYPE_H2,
                            Editable::fromNodeProperty($accordionNode, 'headline')
                        ) : null,
                    Collection::fromSlots(
                        Collection::fromNodes(
                            $accordionNode->findChildNodes(),
                            function (Node $accordionItemNode): Content {
                                return Content::fromNode($accordionItemNode, 'Hoerbiger.HoerbigerCom:ContentSlot');
                            }
                        )
                    )
                ]))


            )
        );
    }

    public function forAccordionItemNode(
        TraversableNodeInterface $accordionItemNode,
        ContentContext $subgraph,
        bool $inBackend,
    ): SlotInterface {
        return new AccordionItem(
            Editable::fromNodeProperty($accordionItemNode, 'headline'),
            new Text(
                TextVariant::VARIANT_FULL_WIDTH,
                Editable::fromNodeProperty($accordionItemNode, 'text'),
                false
            ),
            new Icon('shared', 'dash', IconSize::SIZE_SIZE_1_5),
            new Icon('shared', 'plus', IconSize::SIZE_SIZE_1_5),
            $accordionItemNode->getProperty('isAccordionOpen'),
            $inBackend
        );
    }

}
